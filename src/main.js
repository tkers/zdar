// dependencies
var connect = require("connect");
var serveStatic = require("serve-static");

var app = connect();

app.use(function (req, res, next) {
    console.log(req.method, req.headers.host, req.url);
    next();
});

// serve the static files
app.use(serveStatic("static"));

// start listening
app.listen(5005, function () {
  console.log("Zdar listening on 5005");
});
