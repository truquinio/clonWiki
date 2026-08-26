<?php
/**
 * Backend del diccionario.
 * Ya NO usa MySQL: consulta en vivo la API REST de Wiktionary
 * (proyecto hermano de Wikipedia) para traer definiciones reales.
 *
 * Endpoint usado: https://{idioma}.wiktionary.org/api/rest_v1/page/definition/{palabra}
 * Es gratuita, no requiere API key.
 *
 * Requisitos en el servidor PHP:
 *  - extensión curl habilitada (la mayoría de instalaciones la traen por defecto)
 *  - acceso saliente a internet (puerto 443)
 */

header('Content-Type: application/json; charset=utf-8');

// Idiomas soportados: código Wiktionary => nombre para mensajes
$idiomasSoportados = [
  'es' => 'español',
  'en' => 'inglés',
  'ca' => 'catalán',
  'fr' => 'francés',
  'zh' => 'chino',
  'de' => 'alemán',
  'ru' => 'ruso',
];

$definicion = '';

if (isset($_POST['modo']) && $_POST['modo'] === 'get') {

  $palabra = trim($_POST['palabra'] ?? '');
  $idioma  = $_POST['idioma'] ?? 'es';

  if (!array_key_exists($idioma, $idiomasSoportados)) {
    $idioma = 'es';
  }

  if ($palabra === '') {
    $definicion = '';
  } else {
    $definicion = buscarDefinicion($palabra, $idioma, $idiomasSoportados[$idioma]);
  }
}

echo json_encode(['definicion' => $definicion]);

//----------------------------------------------------------------

/**
 * Consulta Wiktionary y devuelve un texto plano listo para mostrar.
 */
function buscarDefinicion(string $palabra, string $idioma, string $nombreIdioma): string
{
  $url = "https://{$idioma}.wiktionary.org/api/rest_v1/page/definition/" . rawurlencode($palabra);

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 8,
    CURLOPT_HTTPHEADER     => [
      // Wikimedia pide identificar la app que consume la API
      'User-Agent: MiniDiccionarioPHP/1.0 (proyecto educativo)'
    ],
  ]);

  $respuesta = curl_exec($ch);
  $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $errorCurl = curl_error($ch);
  curl_close($ch);

  if ($respuesta === false) {
    return "No se pudo conectar con el diccionario ({$errorCurl}).";
  }

  if ($codigoHttp === 404) {
    return "No se encontró la palabra \"{$palabra}\" en {$nombreIdioma}.";
  }

  if ($codigoHttp !== 200) {
    return "El servicio de diccionario respondió con un error (HTTP {$codigoHttp}).";
  }

  $datos = json_decode($respuesta, true);
  if (!is_array($datos) || empty($datos)) {
    return "No se encontraron definiciones para \"{$palabra}\".";
  }

  // El JSON viene agrupado por el idioma de CADA acepción de la palabra
  // (una misma palabra puede existir en varios idiomas). Priorizamos las
  // acepciones que coincidan con el idioma elegido; si no hay, mostramos
  // todas las que haya encontrado.
  $secciones = array_key_exists($idioma, $datos) ? [$idioma => $datos[$idioma]] : $datos;

  $texto = '';
  foreach ($secciones as $entradas) {
    foreach ($entradas as $entrada) {
      $categoria = $entrada['partOfSpeech'] ?? '';
      if ($categoria !== '') {
        $texto .= "[{$categoria}]\n";
      }
      $n = 1;
      foreach (($entrada['definitions'] ?? []) as $def) {
        $texto .= $n . '. ' . limpiarHtml($def['definition'] ?? '') . "\n";
        $n++;
      }
      $texto .= "\n";
    }
  }

  $texto = trim($texto);
  return $texto !== '' ? $texto : "No se encontraron definiciones para \"{$palabra}\".";
}

/**
 * Wiktionary devuelve las definiciones con etiquetas HTML/enlaces internos.
 * Las quitamos para mostrar solo texto plano en el textarea.
 */
function limpiarHtml(string $html): string
{
  $texto = strip_tags($html);
  $texto = html_entity_decode($texto, ENT_QUOTES, 'UTF-8');
  return trim($texto);
}
