var exec = require('child_process').exec;
module.exports = function phpKillIfExists(port, cb){
  if(cb === undefined){
    cb = function(){};
  }
  exec('lsof -i :' + port,
	  function(error, stdout) {
	    if (error !== null) {
        cb();
        return false;
	    }
	    var pid = stdout.match(/php\s+?([0-9]+)/);
	    if (pid && pid[1]) {
	    	exec('kill ' + pid[1], function() {
          cb();
          return true;
	    	});
	    } else {
        cb();
        return false;
	    }
	});
}
