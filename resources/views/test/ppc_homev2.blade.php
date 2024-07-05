
  <head>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
      
  </head>

  <x-app-layout>

  <!-- portfolio-area -->
    
  {{-- @include('mkt.col_slider') --}}
  @include('components.announceBar')
  @include('mkt.col_slider_v2')
  {{-- @include('components.shopcol_v2') --}}

  @include('components.shopcat')


  <!-- portfolio-area-end -->
      <div class="bottom"></div>

      <div class="footspace"></div>
      
      <footer>
        <div class="footcol">
        <table class="footercontent"  border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td height="140" align="center" bgcolor="#b2b2b2" class="linkGray">
                <br>
                <h2 style="font-size: 17px;">Prempracha’s Collection</h2>
                224 M.3, Chiang mai-San Kamphaeng Rd., <br />
                T.Tonpao, A.San Kamphaeng Chiang mai, 50130 Thailand
              </br>
              </br>
              Tel.: 66 5333 8540, 66 5333 8857
            </br> Email: <a href="mailto:showroom@prempracha.com">info@prempracha.com</a>
          </br> Line ID : showroomprem
          </br></br>Operating Hour: 8:30 - 17:30 hrs, 
        </br>Monday - Saturday<br>
        <div class="footericon">
          
          <a class="icon" href="https://www.facebook.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_fb.png" width="32" height="32" alt="Facebook"/></a> 
          
          <a class="icon" href="https://www.instagram.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_ig.png" width="32" height="32" alt="instagram"/></a><br></td>
        </div>
        </tr>
      </tbody>
    </table>
  </div>
  </footer>



<!-- Initialize Swiper -->
  <script>
  var swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
  });
  </script>      

</x-app-layout>
