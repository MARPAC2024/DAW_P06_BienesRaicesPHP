// Importa utilidades para trabajar con rutas de archivos
import path from 'path'
// Permite leer y crear carpetas o archivos en el sistema
import fs from 'fs'
// Permite buscar archivos usando patrones como asteriscos
import { glob } from 'glob'
// Funciones principales de Gulp para leer, escribir, observar y ordenar tareas
import { src, dest, watch, series } from 'gulp'
// Motor que compila código Sass a CSS
import * as dartSass from 'sass'
// Conector entre Gulp y Sass
import gulpSass from 'gulp-sass'
// Une varios archivos JavaScript en uno solo
import concat from 'gulp-concat'
// Minifica el código JavaScript para que pese menos
import terser from 'gulp-terser'
// Procesa y optimiza imágenes
import sharp from 'sharp'
// Permite cambiar el nombre de los archivos resultantes
import rename from 'gulp-rename'

// Conecta el compilador Sass con el plugin de Gulp
const sass = gulpSass(dartSass)

// Ubicaciones de los archivos que vamos a procesar
const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js'
}

// Compila los archivos SCSS a CSS minificado
export function css( done ) {
    src(paths.scss, {sourcemaps: true})
        .pipe( sass({
            // Comprime el archivo CSS final
            outputStyle: 'compressed'
        }).on('error', sass.logError) ) // Muestra errores en la terminal sin detener la ejecución
        // Guarda el CSS resultante y su mapa en build/css
        .pipe( dest('./build/css', {sourcemaps: '.'}) );
    done()
}

// Une y comprime los scripts de JavaScript
export function js( done ) {
    src(paths.js)
      // Combina todo en un archivo llamado bundle.js
      .pipe(concat('bundle.js'))
      // Reduce y ofusca el código
      .pipe(terser())
      // Le cambia el nombre a bundle.min.js
      .pipe(rename({ suffix: '.min' }))
      // Guarda el archivo final en build/js
      .pipe(dest('./build/js'))
    done()
}

// Busca todas las imágenes de src/img para procesarlas
export async function imagenes(done) {
    const srcDir = './src/img';
    const buildDir = './build/img';
    // Obtiene la lista completa de imágenes
    const images = await glob('./src/img/**/*')

    images.forEach(file => {
        // Detecta la subcarpeta donde estaba la imagen original
        const relativePath = path.relative(srcDir, path.dirname(file));
        const outputSubDir = path.join(buildDir, relativePath);
        procesarImagenes(file, outputSubDir);
    });
    done();
}

// Genera versiones en JPG, WebP y AVIF de cada imagen
function procesarImagenes(file, outputSubDir) {
    // Si la carpeta de destino no existe, la crea
    if (!fs.existsSync(outputSubDir)) {
        fs.mkdirSync(outputSubDir, { recursive: true })
    }
    const baseName = path.basename(file, path.extname(file))
    const extName = path.extname(file)

    if (extName.toLowerCase() === '.svg') {
        // Si es SVG solo lo copia tal cual
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        fs.copyFileSync(file, outputFile);
    } else {
        // Para JPG y PNG genera tres copias optimizadas
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
        const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);
        const options = { quality: 80 };

        sharp(file).jpeg(options).toFile(outputFile);
        sharp(file).webp(options).toFile(outputFileWebp);
        sharp(file).avif().toFile(outputFileAvif);
    }
}

// Revisa cambios en los archivos y corre la tarea correspondiente
export function dev() {
    // Si cambia un SCSS corre css
    watch( paths.scss, css );
    // Si cambia un JS corre js
    watch( paths.js, js );
    // Si agregas una imagen corre imagenes
    watch('src/img/**/*.{png,jpg}', imagenes)
}

// Ejecuta primero js, luego css, luego imagenes y al final activa dev
export default series( js, css, imagenes, dev )