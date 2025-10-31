        
        <script>
            $(function() {
                $(".video").click(function () {
                    var theModal = $(this).data("target"),
                        videoSRC = $(this).attr("data-video"),
                        videoSRCauto = videoSRC + "";

                    $(theModal + ' source').attr('src', videoSRCauto);
                    $(theModal + ' video').load();
                    $(theModal + ' button.close').click(function () {
                        $(theModal + ' source').attr('src', videoSRC);
                    });
                });
            }); 


            function showIcon(element) {
                var playIcon = element.querySelector('.playIcon');
                playIcon.style.visibility = 'visible';
            }

            function hideIcon(element) {
                var playIcon = element.querySelector('.playIcon');
                playIcon.style.visibility = 'hidden';
            }
        

            function togglePasswordVisibility() {
                var passwordInput = document.getElementById("passwordInput");
                var passwordIcon = document.getElementById("passwordIcon");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordIcon.classList.remove("fa-eye-slash");
                    passwordIcon.classList.add("fa-eye");
                } else {
                    passwordInput.type = "password";
                    passwordIcon.classList.remove("fa-eye");
                    passwordIcon.classList.add("fa-eye-slash");
                }
            }

            // <?php if(isset($_SESSION['user'])) :?>
            //     document.querySelector(".imageUserHeader").addEventListener('click', ()=>{
            //         window.location.href = "<?=$base?>/<?=$_SESSION['user']['user_name'] ?>";
            //     })
            // <?php endif ?>
      


        </script>     
        
   

        <?php
            if ($URL === '/artuniverse/') {
                echo '<footer>
                        <img src="'.$base.'/public/img/saturn.webp" alt="">
                    </footer>';
            }
        ?>

        
    </body>
</html>

