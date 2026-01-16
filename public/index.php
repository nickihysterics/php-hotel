<?php

declare(strict_types=1);

require __DIR__ . '/../config/autoload.php';

$title = 'Отель';
$active = 'home';

require __DIR__ . '/../templates/site/header.php';
?>
    <section class="home-slider owl-carousel">
      <div class="slider-item" style="background-image:url(images/Free.jpg);">
        <div class="overlay"></div>
        <div class="container">
          <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-12 ftco-animate text-center">
              <div class="text mb-5 pb-3">
                <h1 class="mb-3">Добро пожаловать!</h1>
                <h2>Вы находитесь на собственном сайте гостиницы</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image:url(images/Sov.jpg);">
        <div class="overlay"></div>
        <div class="container">
          <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-12 ftco-animate text-center">
              <div class="text mb-5 pb-3">
                <h1 class="mb-3">Мы рады что вы обратились именно к нам</h1>
                <h2>Здесь вы получите необходимую вам информацию</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftc-no-pb ftc-no-pt">
      <div class="container">
        <div class="row">
          <div class="col-md-5 p-md-5 img img-2 d-flex justify-content-center align-items-center" style="background-image: url(images/Lano.jpg);">
          </div>
          <div class="col-md-7 py-5 wrap-about pb-md-5 ftco-animate">
            <div class="heading-section heading-section-wo-line pt-md-5 pl-md-5 mb-5">
              <div class="ml-md-0">
                <span class="subheading">Отель в Советском</span>
                <h2 class="mb-4">Добро пожаловать в наш отель</h2>
              </div>
            </div>
            <div class="pb-md-5">
              <p>Отель расположен в городе Советском. К услугам гостей номера различной ценовой категории. Отель работает с 2010 года и считается одной из ведущих гостиниц высокого класса в Советском районе.</p>
              <p>Уникальная архитектура отеля гармонично сочетает классику и современность. Отель находится недалеко от железнодорожного вокзала, а также граничит с автодорогой, ведущей в г. Югорск, и аэропортом.</p>
              <ul class="ftco-social d-flex">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-google-plus"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row d-flex">
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services py-4 d-block text-center">
              <div class="d-flex justify-content-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-reception-bell"></span>
                </div>
              </div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Стойка регистрации работает 24/7</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services py-4 d-block text-center">
              <div class="d-flex justify-content-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-serving-dish"></span>
                </div>
              </div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Рядом с гостиницей есть кафе</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services py-4 d-block text-center">
              <div class="d-flex justify-content-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-car"></span>
                </div>
              </div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Присутствует парковка</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services py-4 d-block text-center">
              <div class="d-flex justify-content-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-spa"></span>
                </div>
              </div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Присутствуют душевые кабинки</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Отель в Советском</span>
            <h2 class="mb-4">Комнаты</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-sm col-md-6 col-lg-4 ftco-animate">
            <div class="room">
              <a href="/rooms-single.php" class="img d-flex justify-content-center align-items-center" style="background-image: url(images/Stan.jpg);">
                <div class="icon d-flex justify-content-center align-items-center">
                  <span class="icon-search2"></span>
                </div>
              </a>
              <div class="text p-3 text-center">
                <h3 class="mb-3"><a href="/rooms-single.php">Стандарт</a></h3>
                <p><span class="price mr-2">2500р</span> <span class="per">в сутки</span></p>
                <ul class="list">
                  <li><span>Макс:</span> 2 Человека</li>
                  <li><span>Кровать:</span> 1</li>
                </ul>
                <hr>
                <p class="pt-1"><a href="/rooms-single.php" class="btn-custom">Дополнительная информация <span class="icon-long-arrow-right"></span></a></p>
              </div>
            </div>
          </div>
          <div class="col-sm col-md-6 col-lg-4 ftco-animate">
            <div class="room">
              <a href="/rooms-single.php" class="img d-flex justify-content-center align-items-center" style="background-image: url(images/StUl.jpg);">
                <div class="icon d-flex justify-content-center align-items-center">
                  <span class="icon-search2"></span>
                </div>
              </a>
              <div class="text p-3 text-center">
                <h3 class="mb-3"><a href="/rooms-single.php">Стандарт улучшенный</a></h3>
                <p><span class="price mr-2">3000р</span> <span class="per">в сутки</span></p>
                <ul class="list">
                  <li><span>Макс:</span> 2 Человека</li>
                  <li><span>Кровати:</span> 1</li>
                </ul>
                <hr>
                <p class="pt-1"><a href="/rooms-single.php" class="btn-custom">Дополнительная информация <span class="icon-long-arrow-right"></span></a></p>
              </div>
            </div>
          </div>
          <div class="col-sm col-md-6 col-lg-4 ftco-animate">
            <div class="room">
              <a href="/rooms-single.php" class="img d-flex justify-content-center align-items-center" style="background-image: url(images/Lux.jpg);">
                <div class="icon d-flex justify-content-center align-items-center">
                  <span class="icon-search2"></span>
                </div>
              </a>
              <div class="text p-3 text-center">
                <h3 class="mb-3"><a href="/rooms-single.php">Люкс</a></h3>
                <p><span class="price mr-2">3000р</span> <span class="per">в сутки</span></p>
                <ul class="list">
                  <li><span>Макс:</span> 4 Человека</li>
                  <li><span>Кровати:</span> 2</li>
                </ul>
                <hr>
                <p class="pt-1"><a href="/rooms-single.php" class="btn-custom">Дополнительная информация <span class="icon-long-arrow-right"></span></a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_1.jpg);">
      <div class="overlay"></div>
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Вся основная информация о нас</span>
            <h2 class="mb-4">Факты</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 text-center">
              <div class="text">
                <strong class="number" data-number="50">0</strong>
                <span>Гостей</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 text-center">
              <div class="text">
                <strong class="number" data-number="3000">0</strong>
                <span>Максимальная цена (руб.)</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 text-center">
              <div class="text">
                <strong class="number" data-number="10">0</strong>
                <span>Персонал</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
            <div class="block-18 text-center">
              <div class="text">
                <strong class="number" data-number="15">0</strong>
                <span>Лет на рынке</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Персонал гостиницы</span>
            <h2 class="mb-4">Управляющий персонал</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="staff">
              <div class="img" style="background-image: url(images/staff-1.jpg);"></div>
              <div class="text pt-4">
                <h3>Анна</h3>
                <span class="position mb-2">Администратор</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="staff">
              <div class="img" style="background-image: url(images/staff-2.jpg);"></div>
              <div class="text pt-4">
                <h3>София</h3>
                <span class="position mb-2">Администратор</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="staff">
              <div class="img" style="background-image: url(images/staff-3.jpg);"></div>
              <div class="text pt-4">
                <h3>Ирина</h3>
                <span class="position mb-2">Администратор</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 ftco-animate">
            <div class="staff">
              <div class="img" style="background-image: url(images/staff-4.jpg);"></div>
              <div class="text pt-4">
                <h3>Елена</h3>
                <span class="position mb-2">Администратор</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<?php
require __DIR__ . '/../templates/site/footer.php';
