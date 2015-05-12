






;(function (e) {





    e.fn.extend({
        jEditMakeAbsolute: function (t) {
            return this.each(function () {
                var n = e(this);
                var r;
                if (t) {
                    r = n.offset()
                } else {
                    r = n.position()
                }
                n.css({
                    position: "absolute",
                    marginLeft: 0,
                    marginTop: 0,
                    top: r.top,
                    left: r.left,
                    width: n.width(),
                    height: n.height()
                });
                if (t) {
                    n.detach().appendTo("body")
                }
            })
        }
    });



    e(document).ready(function () {
        var t = 200;
        var n = 100;
        var r = function (r, i) {
            var s, o, u, a, f, l, c, h, p, d, v, m, g, y, b;
            m = function (e) {
                return c < e.top && f < e.left && l > e.left + t && a > e.top + n
            };
            s = e(i);
            y = e.extend({}, s.offset(), {
                width: i.offsetWidth,
                height: i.offsetHeight
            });
            c = e(document).scrollTop();
            f = e(document).scrollLeft();
            l = f + e(window).width();
            a = c + e(window).height();
            h = {
                top: y.top - n,
                left: y.left + y.width / 2 - t / 2
            };
            p = {
                top: y.top + y.height,
                left: y.left + y.width / 2 - t / 2
            };
            d = {
                top: y.top + y.height / 2 - n / 2,
                left: y.left - t
            };
            v = {
                top: y.top + y.height / 2 - n / 2,
                left: y.left + y.width
            };
            o = m(h);
            u = m(p);
            g = m(d);
            b = m(v);
            if (o) {
                return "top"
            } else {
                if (u) {
                    return "bottom"
                } else {
                    if (g) {
                        return "left"
                    } else {
                        if (b) {
                            return "right"
                        } else {
                            return "right"
                        }
                    }
                }
            }
        };


        e(".jmoddiv").on({
            mouseenter: function () {



                var t = e(this).data("jmodediturl");
                var n = e(this).data("jmodtip");

               // e(".btn.jmodedit").clearQueue().tooltip("destroy").remove();

                console.log(e(this));

                e(this).addClass("jmodinside")

                .prepend('<a class="btn jmodedit" href="#" target="_blank"><span class="fa fa-edit"></span></a>').children(":first").attr("href", t).attr("title", n)

                .tooltip({
                    container: false,
                    placement: r
                })

               // .jEditMakeAbsolute(true)
                ;

                e(this).removeClass("jmodinside");

                e(".btn.jmodedit").on({

                    mouseenter: function () {
                        e(this).clearQueue()
                    },
                    mouseleave: function () {
                        e(this).delay(500).queue(function (t) {
                            e(this).tooltip("destroy").remove();
                            t()
                        })
                    }
                })
            },

            mouseleave: function () {



                e(".btn.jmodedit").delay(500).queue(function (t) {
                    e(this).tooltip("destroy").remove();
                    t()
                })
            }
        });



        var i = null;
        e(".jmoddiv[data-jmenuedittip] .nav li,.jmoddiv[data-jmenuedittip].nav li,.jmoddiv[data-jmenuedittip] .nav .nav-child li,.jmoddiv[data-jmenuedittip].nav .nav-child li").on({
            mouseenter: function () {
                var t = /\bitem-(\d+)\b/.exec(e(this).attr("class"));
                if (typeof t[1] == "string") {
                    var n = e(this).closest(".jmoddiv");
                    var r = n.data("jmodediturl");
                    var s = r.replace(/\/index.php\?option=com_modules&view=module([^\d]+)\d+$/, "/index.php?option=com_menus&view=item$1" + t[1])
                }
                var o = n.data("jmenuedittip").replace("%s", t[1]);
                var u = e('<div><a class="btn jfedit-menu" href="#" target="_blank"><span class="icon-edit"></span></a></div>');
                u.children("a.jfedit-menu").prop("href", s).prop("title", o);
                if (i) {
                    e(i).popover("hide")
                }
                e(this).popover({
                    html: true,
                    content: u.html(),
                    container: "body",
                    trigger: "manual",
                    animation: false,
                    placement: "bottom"
                }).popover("show");
                i = this;
                e("body>div.popover").on({
                    mouseenter: function () {
                        if (i) {
                            e(i).clearQueue()
                        }
                    },
                    mouseleave: function () {
                        if (i) {
                            e(i).popover("hide")
                        }
                    }
                }).find("a.jfedit-menu").tooltip({
                    container: false,
                    placement: "bottom"
                })
            },


            mouseleave: function () {
                e(this).delay(1500).queue(function (t) {
                    e(this).popover("hide");
                    t()
                })
            }
        })


    })
})(jQuery)

