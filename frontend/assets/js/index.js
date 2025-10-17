var app = $.spapp({
    defaultView: "#page1",
    templateDir: "./pages/"
});
app.run();

app.route({
    view: "page1",
    onReady: function() { nav("pg1"); }
});


app.route({
    view: "page2",
    onReady: function() { nav("pg2"); }
});


app.route({
    view: "page3",
    onReady: function() { nav("pg3"); }
});


app.route({
    view: "page4",
    onReady: function() { nav("pg4"); }
});


app.route({
    view: "page5",
    onReady: function() { nav("pg5"); }
});


app.route({
    view: "page6",
    onReady: function() { nav("pg6"); }
});


app.route({
    view: "page7",
    onReady: function() { nav("pg7"); }
});

app.route({
    view: "page8",
    onReady: function() { admin(); }
});

