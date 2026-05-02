# Instrucciones para activar los exportes Excel y PDF

## 1. Instalar PhpSpreadsheet (solo para Excel)

En la raíz de tu proyecto Laravel, ejecuta:

```bash
composer require phpoffice/phpspreadsheet
```

Esto instala la librería para generar archivos Excel (.xlsx).

## 2. El PDF no necesita nada extra

El PDF funciona abriendo una página HTML optimizada para impresión.
El navegador se encarga de todo — solo presiona **Ctrl+P** (o el botón "Imprimir / Guardar PDF")
y en la impresora selecciona **"Guardar como PDF"**.

## 3. Dónde aparecen los botones

| Vista | Botones disponibles |
|---|---|
| Listado Activos Fijos | 📄 PDF · 📊 Excel (respeta los filtros activos) |
| Listado Activos Menores | 📄 PDF · 📊 Excel (respeta los filtros activos) |
| Listado Colaboradores | 📊 Excel |
| Perfil de un Colaborador | 📄 PDF Activos (solo sus activos actuales) |
| Dashboard | 📄 Mantenimientos (historial completo) |

## 4. Nota importante sobre los filtros

Cuando filtras activos por tipo o estado y luego haces clic en PDF o Excel,
el reporte incluye **solo los activos filtrados**, no todos.
Esto es útil para reportes específicos, por ejemplo:
- Solo laptops disponibles
- Solo activos asignados del departamento TI

## 5. Para producción (MySQL)

No hay que cambiar nada en el código.
Solo actualiza estas líneas en tu archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_bd
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contrasena
```
