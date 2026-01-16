<?php

declare(strict_types=1);

require __DIR__ . '/../config/autoload.php';

$title = 'Номер';
$active = 'rooms';

require __DIR__ . '/../templates/site/header.php';
?>
    <div class="hero-wrap" style="background-image: url('images/bg_1.jpg');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text d-flex align-itemd-end justify-content-center">
          <div class="col-md-9 ftco-animate text-center d-flex align-items-end justify-content-center">
            <div class="text">
              <h1 class="mb-4 bread">Номер</h1>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="row">
              <div class="col-md-12 ftco-animate">
                <h2 class="mb-4">Семейный номер</h2>
                <div class="single-slider owl-carousel">
                  <div class="item">
                    <div class="room-img" style="background-image: url(images/room-1.jpg);"></div>
                  </div>
                  <div class="item">
                    <div class="room-img" style="background-image: url(images/room-2.jpg);"></div>
                  </div>
                  <div class="item">
                    <div class="room-img" style="background-image: url(images/room-3.jpg);"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 room-single mt-4 mb-5 ftco-animate">
                <p>Просторный семейный номер с зоной отдыха и рабочим местом. Подходит для пары или семьи, в номере предусмотрено все для комфортного проживания.</p>
                <div class="d-md-flex mt-5 mb-5">
                  <ul class="list">
                    <li><span>Макс.:</span> 3 человека</li>
                    <li><span>Площадь:</span> 45 м2</li>
                  </ul>
                  <ul class="list ml-md-5">
                    <li><span>Вид:</span> панорамный</li>
                    <li><span>Кровати:</span> 1</li>
                  </ul>
                </div>
                <p>В номере: удобная кровать, беспроводной интернет, кондиционер, телевизор и собственная ванная комната. По запросу доступны дополнительное место и детская кроватка.</p>
              </div>
              <div class="col-md-12 room-single ftco-animate mb-5 mt-4">
                <h3 class="mb-4">Виртуальный тур</h3>
                <div class="block-16">
                  <figure>
                    <img src="images/room-4.jpg" alt="Фото номера" class="img-fluid">
                    <a href="https://vimeo.com/45830194" class="play-button popup-vimeo"><span class="icon-play"></span></a>
                  </figure>
                </div>
              </div>

              <div class="col-md-12 room-single ftco-animate mb-5 mt-5">
                <h4 class="mb-4">Доступные номера</h4>
                <div class="row">
                  <div class="col-sm col-md-6 ftco-animate">
                    <div class="room">
                      <a href="/rooms.php" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(images/room-1.jpg);">
                        <div class="icon d-flex justify-content-center align-items-center">
                          <span class="icon-search2"></span>
                        </div>
                      </a>
                      <div class="text p-3 text-center">
                        <h3 class="mb-3"><a href="/rooms.php">Номер люкс</a></h3>
                        <p><span class="price mr-2">3000р</span> <span class="per">в сутки</span></p>
                        <hr>
                        <p class="pt-1"><a href="/rooms-single.php" class="btn-custom">Подробнее о номере <span class="icon-long-arrow-right"></span></a></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm col-md-6 ftco-animate">
                    <div class="room">
                      <a href="/rooms.php" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(images/room-2.jpg);">
                        <div class="icon d-flex justify-content-center align-items-center">
                          <span class="icon-search2"></span>
                        </div>
                      </a>
                      <div class="text p-3 text-center">
                        <h3 class="mb-3"><a href="/rooms.php">Семейный номер</a></h3>
                        <p><span class="price mr-2">2500р</span> <span class="per">в сутки</span></p>
                        <hr>
                        <p class="pt-1"><a href="/rooms-single.php" class="btn-custom">Подробнее о номере <span class="icon-long-arrow-right"></span></a></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<?php
require __DIR__ . '/../templates/site/footer.php';
