var gulp = require('gulp');
var connect = require('gulp-connect-php');
var openurl = require('openurl').open;
var argv = require('yargs').argv;
var phpKillIfExists = require('./utils/phpKillIfExists');

var hostname = 'localhost';
var port = argv.p || '4123';

gulp.task('disconnect', function(cb) {
  phpKillIfExists(port, cb);
});

gulp.task('connect', ['disconnect'], function(cb) {
  connect.server({
    hostname: hostname,
    port: port,
    base: './public',
    keepalive: true
  }, function() {
    argv.open && openurl('http://' + hostname + ':' + port);
    cb();
  });
});

gulp.task('default', ['connect']);
