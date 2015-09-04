<div id="container">
    {style src="slick.css"}
    {script src="slick.js"}
    {literal}
    <script type="text/javascript">
        $(document).ready(function(){
            $('.rockyslider').slick({
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: false,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            prevArrow: '<img class="slick-prev" src="{/literal}{$smarty.const._IMAGES_URL_}{literal}/prev_new.png">',
            nextArrow: '<img class="slick-next" src="{/literal}{$smarty.const._IMAGES_URL_}{literal}/next_new.png">',
            centerMode: true,
            variableWidth: true,
            responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    arrows: false,
                    centerMode: false,
                    variableWidth: false,
                    mobileFirst: true,
                    slidesToShow: 1
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    arrows: false,
                    centerMode: false,
                    variableWidth: false,
                    mobileFirst: true,
                    slidesToShow: 1
                  }
                }
              ]
          });
        });
    </script>
    {/literal}
    <div class="rockyslider">
        {foreach name="results" from=$slider_image item=row}
            <div>
                <img src="{$smarty.const._IMAGES_URL_}/sliders/{$row.image}" alt="{$row.image}" />
            </div>
        {/foreach}
    </div>
</div>