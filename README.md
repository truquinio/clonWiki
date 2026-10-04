<div align="center">

# 📜 Mini Diccionario

### Diccionario web multiidioma conectado a Wiktionary

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat-square)
![jQuery](https://img.shields.io/badge/jQuery-AJAX-0769AD?style=flat-square)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square)
![Wiktionary](https://img.shields.io/badge/datos-Wiktionary-000000?style=flat-square)

</div>

---

## 📖 Qué hace

Mini Diccionario recibe una palabra y un idioma, consulta Wiktionary desde un backend PHP y muestra las definiciones como texto legible.

No utiliza base de datos propia ni necesita API key.

**Idiomas implementados:** Español · Inglés · Catalán · Francés · Chino · Alemán · Ruso

## 📸 Capturas

| Inicio | Resultado |
| --- | --- |
| ![Vista inicial del diccionario](screenshots/vacio.png) | ![Resultado de una búsqueda](screenshots/resultado.png) |

## 🔄 Cómo funciona

~~~mermaid
flowchart TD
    U["Usuario"] --> F["HTML + Bootstrap"]
    F --> J["jQuery / AJAX"]
    J --> P["PHP + cURL"]
    P --> W["Wiktionary REST API"]
    W --> P
    P --> F
~~~

Endpoint utilizado:

~~~text
https://{idioma}.wiktionary.org/api/rest_v1/page/definition/{palabra}
~~~

## 🧱 Stack

**Frontend:** HTML5 · Bootstrap 5 · CSS · jQuery  
**Backend:** PHP · cURL  
**Datos:** Wiktionary REST API

## ▶️ Ejecutar localmente

<details>
<summary><strong>Ver instrucciones</strong></summary>

### Requisitos

- PHP 7.4 o superior
- extensión curl
- acceso HTTPS a internet

~~~bash
git clone https://github.com/truquinio/clonWiki.git
cd clonWiki
php -S localhost:8000
~~~

Abre http://localhost:8000.

También puede servirse desde Apache/Nginx o entornos locales como XAMPP.

</details>

## 🗂️ Estructura

<details>
<summary><strong>Ver estructura</strong></summary>

~~~text
.
├── index.html
├── css/
│   └── style.css
├── js/
│   └── funciones.js
├── php/
│   └── diccionario.php
└── screenshots/
~~~

</details>

## ⚠️ Limitaciones

- la cobertura depende de cada edición de Wiktionary;
- el proyecto depende del endpoint REST externo;
- no implementa caché ni base de datos local.

## 📜 Licencia

El código de este proyecto se publica bajo la [Licencia MIT](LICENSE).

Los contenidos obtenidos desde Wiktionary siguen sujetos a las licencias y términos aplicables de Wikimedia/Wiktionary.

---

**Federico Trucco / [@truquinio](https://github.com/truquinio)**

---

**by [truquinio](https://github.com/truquinio)** · [LinkedIn](https://www.linkedin.com/in/federico-trucco/)
