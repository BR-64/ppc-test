import "./bootstrap";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import { get, post } from "./http.js";

Alpine.plugin(collapse);

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {
  Alpine.data("toast", () => ({
    visible: false,
    delay: 5000,
    percent: 0,
    interval: null,
    timeout: null,
    message: null,
    close() {
      this.visible = false;
      clearInterval(this.interval);
    },
    show(message) {
      this.visible = true;
      this.message = message;

      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      this.timeout = setTimeout(() => {
        this.visible = false;
        this.timeout = null;
      }, this.delay);
      const startDate = Date.now();
      const futureDate = Date.now() + this.delay;
      this.interval = setInterval(() => {
        const date = Date.now();
        this.percent = ((date - startDate) * 100) / (futureDate - startDate);
        if (this.percent >= 100) {
          clearInterval(this.interval);
          this.interval = null;
        }
      }, 30);
    },
  }));

  Alpine.data("productItem", (product) => {
    return {
      product,
      addToCart(quantity = 1) {
        post(this.product.addToCartUrl, { quantity })
          .then((result) => {
            this.$dispatch("cart-change", { count: result.count });
            this.$dispatch("notify", {
              message: "The item was added into the cart",
            });
          })
          .catch((response) => {
            console.log(response);
          });
      },
      removeItemFromCart() {
        post(this.product.removeUrl).then((result) => {
          this.$dispatch("notify", {
            message: "The item was removed from cart",
          });
          this.$dispatch("cart-change", { count: result.count });
          this.cartItems = this.cartItems.filter((p) => p.id !== product.id);
        });
      },
      changeQuantity() {
        post(this.product.updateQuantityUrl, {
          quantity: product.quantity,
        }).then((result) => {
          this.$dispatch("cart-change", { count: result.count });
          this.$dispatch("notify", {
            message: "The item quantity was updated",
          });
        });
      },
    };
  });
});

Alpine.start();

// j query magnify ---------------------------------
var perc = 40;
$("ul.thumb li").hover(
  function () {
    $("ul.thumb li").find(".thumbnail-wrap").css({
      "z-index": "0",
    });
    $(this).find(".thumbnail-wrap").css({
      "z-index": "10",
    });
    var imageval = $(this)
      .find(".thumbnail-wrap")
      .css("background-image")
      .slice(5);
    var img;
    var thisImage = this;
    img = new Image();
    img.src = imageval.substring(0, imageval.length - 2);
    img.onload = function () {
      var imgh = this.height * (perc / 100);
      var imgw = this.width * (perc / 100);
      $(thisImage)
        .find(".thumbnail-wrap")
        .addClass("hover")
        .stop()
        .animate(
          {
            marginTop: "-" + imgh / 4 + "px",
            marginLeft: "-" + imgw / 4 + "px",
            width: imgw + "px",
            height: imgh + "px",
          },
          200
        );
    };
  },
  function () {
    var thisImage = this;
    $(this)
      .find(".thumbnail-wrap")
      .removeClass("hover")
      .stop()
      .animate(
        {
          marginTop: "0",
          marginLeft: "0",
          top: "0",
          left: "0",
          width: "100px",
          height: "100px",
          padding: "5px",
        },
        400,
        function () {}
      );
  }
);

//Show thumbnail in fullscreen
$("ul.thumb li .thumbnail-wrap").click(function () {
  var imageval = $(this).css("background-image").slice(5);
  imageval = imageval.substring(0, imageval.length - 2);
  $(".thumbnail-zoomed-image img").attr({
    src: imageval,
  });
  $(".thumnail-zoomed-wrapper").fadeIn();
  return false;
});

//Close fullscreen preview
$(".thumnail-zoomed-wrapper .close-image-zoom").click(function () {
  $(".thumnail-zoomed-wrapper").hide();
  return false;
});

// end j query magnify ---------------------------------
