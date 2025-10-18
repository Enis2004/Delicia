var app = $.spapp({
    defaultView: "#page1",
    templateDir: "./pages/"
});
app.run();

app.route({
    view: "page1",
    onReady: function() {  }
});


app.route({
    view: "page2",
    onReady: function() {  }
});


app.route({
    view: "page3",
    onReady: function() {  }
});


app.route({
    view: "page4",
    onReady: function() {  }
});


app.route({
    view: "page5",
    onReady: function() {  }
});


app.route({
    view: "page6",
    onReady: function() {  }
});


app.route({
    view: "page7",
    onReady: function() {  }
});

app.route({
    view: "page8",
    onReady: function() { admin(); }
});

