"use strict";

//import yargs          from 'yargs';

import _yargs from "yargs";
import { hideBin } from "yargs/helpers";
const yargs = _yargs(hideBin(process.argv));

import browser from "browser-sync";
import gulp from "gulp";
import { rimraf } from "rimraf";
import rev from "gulp-rev";
import yaml from "js-yaml";
import fs from "fs";
import webpackStream from "webpack-stream";
import webpack2 from "webpack";
import named from "vinyl-named";
import log from "fancy-log";
import colors from "ansi-colors";
import sass from "gulp-dart-sass";
import sourcemaps from "gulp-sourcemaps";
import gulpif from "gulp-if";
import autoprefixer from "gulp-autoprefixer";
import cleanCss from "gulp-clean-css";
import uglify from "gulp-uglify";
import imagemin from "gulp-imagemin";
import phpcs from "gulp-phpcs";
import phpcbf from "gulp-phpcbf";
import imageminGifsicle from "imagemin-gifsicle";
import imageminJpegtran from "imagemin-jpegtran";
import imageminOptipng from "imagemin-optipng";
import imageminSvgo from "imagemin-svgo";

// Check for --production flag
const PRODUCTION = !!yargs.argv.production;

// Check for --development flag unminified with sourcemaps
const DEV = !!yargs.argv.dev;

// Load settings from settings.yml
const { BROWSERSYNC, REVISIONING, PATHS } = loadConfig();

// Check if file exists synchronously
function checkFileExists(filepath) {
  let flag = true;
  try {
    fs.accessSync(filepath, fs.F_OK);
  } catch (e) {
    flag = false;
  }
  return flag;
}

// Load default or custom YML config file
function loadConfig() {
  log("Loading config file...");

  if (checkFileExists("config.yml")) {
    // config.yml exists, load it
    log(
      colors.bold(colors.cyan("config.yml")),
      "exists, loading",
      colors.bold(colors.cyan("config.yml"))
    );
    let ymlFile = fs.readFileSync("config.yml", "utf8");
    return yaml.load(ymlFile);
  } else if (checkFileExists("config-default.yml")) {
    // config-default.yml exists, load it
    log(
      colors.bold(colors.cyan("config.yml")),
      "does not exist, loading",
      colors.bold(colors.cyan("config-default.yml"))
    );
    let ymlFile = fs.readFileSync("config-default.yml", "utf8");
    return yaml.load(ymlFile);
  } else {
    // Exit if config.yml & config-default.yml do not exist
    log("Exiting process, no config file exists.");
    log("Error Code:", err.code);
    process.exit(1);
  }
}

// Delete the "dist" folder
// This happens every time a build starts
function clean(done) {
  rimraf(PATHS.dist).then(() => {
    done();
  });
}

// Copy files out of the assets folder
// This task skips over the "images", "js", and "scss" folders, which are parsed separately
function copy() {
  return gulp.src(PATHS.assets).pipe(gulp.dest(PATHS.dist + "/assets"));
}

// Compile Sass into CSS
// In production, the CSS is compressed
function sass_fcn() {
  return gulp
    .src(["src/assets/scss/app.scss", "src/assets/scss/editor.scss"])
    .pipe(sourcemaps.init())
    .pipe(
      sass.sync({
        includePaths: PATHS.sass,
      }).on("error", sass.logError)
    )
    .pipe(autoprefixer())

    .pipe(gulpif(PRODUCTION, cleanCss({ compatibility: "ie9" })))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(gulpif((REVISIONING && PRODUCTION) || (REVISIONING && DEV), rev()))
    .pipe(gulp.dest(PATHS.dist + "/assets/css"))
    .pipe(
      gulpif(
        (REVISIONING && PRODUCTION) || (REVISIONING && DEV),
        rev.manifest()
      )
    )
    .pipe(gulp.dest(PATHS.dist + "/assets/css"))
    .pipe(browser.reload({ stream: true }));
}

// Combine JavaScript into one file
// In production, the file is minified
const webpack = {
  config: {
    module: {
      rules: [
        {
          test: /.js$/,
          loader: "babel-loader",
          exclude: /node_modules(?![\\\/]foundation-sites)/,
        },
      ],
    },
    externals: {
      jquery: "jQuery",
    },
    mode: "production",
  },

  changeHandler(err, stats) {
    log(
      "[webpack]",
      stats.toString({
        colors: true,
      })
    );

    browser.reload();
  },

  build() {
    return gulp
      .src(PATHS.entries)
      .pipe(named())
      .pipe(webpackStream(webpack.config, webpack2))
      .pipe(
        gulpif(
          PRODUCTION,
          uglify().on("error", (e) => {
            console.log(e);
          })
        )
      )
      .pipe(gulpif((REVISIONING && PRODUCTION) || (REVISIONING && DEV), rev()))
      .pipe(gulp.dest(PATHS.dist + "/assets/js"))
      .pipe(
        gulpif(
          (REVISIONING && PRODUCTION) || (REVISIONING && DEV),
          rev.manifest()
        )
      )
      .pipe(gulp.dest(PATHS.dist + "/assets/js"));
  },

  watch() {
    const watchConfig = Object.assign(webpack.config, {
      watch: true,
      devtool: "inline-source-map",
    });

    return gulp
      .src(PATHS.entries)
      .pipe(named())
      .pipe(
        webpackStream(watchConfig, webpack2, webpack.changeHandler).on(
          "error",
          (err) => {
            log(
              "[webpack:error]",
              err.toString({
                colors: true,
              })
            );
          }
        )
      )
      .pipe(gulp.dest(PATHS.dist + "/assets/js"));
  },
};

gulp.task("webpack:build", webpack.build);
gulp.task("webpack:watch", webpack.watch);

// Copy images to the "dist" folder
// In production, the images are compressed
function images() {
  return gulp
    .src("src/assets/images/**/*")
    .pipe(
      gulpif(
        PRODUCTION,
        imagemin([
          imageminGifsicle({
            interlaced: true,
          }),
          imageminJpegtran({
            progressive: true,
          }),
          imageminOptipng({
            optimizationLevel: 5,
          }),
          imageminSvgo({
            plugins: [
              { name: 'cleanupAttrs', active: true },
              { name: 'removeComments', active: true },
            ],
          }),
        ])
      )
    )
    .pipe(gulp.dest(PATHS.dist + "/assets/images"));
}

// PHP Code Sniffer task
gulp.task("phpcs", function () {
  return gulp
    .src(PATHS.phpcs)
    .pipe(
      phpcs({
        bin: "wpcs/vendor/bin/phpcs",
        standard: "./codesniffer.ruleset.xml",
        showSniffCode: true,
      })
    )
    .pipe(phpcs.reporter("log"));
});

// PHP Code Beautifier task
gulp.task("phpcbf", function () {
  return gulp
    .src(PATHS.phpcs)
    .pipe(
      phpcbf({
        bin: "wpcs/vendor/bin/phpcbf",
        standard: "./codesniffer.ruleset.xml",
        warningSeverity: 0,
      })
    )
    .on("error", log)
    .pipe(gulp.dest("."));
});

// Start BrowserSync to preview the site in
function server(done) {
  browser.init({
    proxy: BROWSERSYNC.url,

    ui: {
      port: 8080,
    },
  });
  done();
}

// Reload the browser with BrowserSync
function reload(done) {
  browser.reload();
  done();
}

// Watch for changes to static assets, pages, Sass, and JavaScript
function watch() {
  gulp.watch(PATHS.assets, copy);
  gulp
    .watch("src/assets/scss/**/*.scss", sass_fcn)
    .on("change", (path) =>
      log("File " + colors.bold(colors.magenta(path)) + " changed.")
    )
    .on("unlink", (path) =>
      log("File " + colors.bold(colors.magenta(path)) + " was removed.")
    );
  gulp
    .watch("**/*.php", reload)
    .on("change", (path) =>
      log("File " + colors.bold(colors.magenta(path)) + " changed.")
    )
    .on("unlink", (path) =>
      log("File " + colors.bold(colors.magenta(path)) + " was removed.")
    );
  gulp.watch("src/assets/images/**/*", gulp.series(images, reload));
}

// Build the "dist" folder by running all of the below tasks
gulp.task(
  "build",
  gulp.series(clean, gulp.parallel(sass_fcn, "webpack:build", images, copy))
);

// Build the site, run the server, and watch for file changes
gulp.task(
  "default",
  gulp.series("build", server, gulp.parallel("webpack:watch", watch))
);
