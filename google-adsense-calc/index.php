<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require 'env.php';

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Kalkulator Google Adsense - Wevelope</title>

    <!-- Bootstrap core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://unpkg.com/vue@3"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>

window.onload = function() {

// DOM Ready here
Vue.createApp({
  data: function() {
    return {
      pv: 1000,
      ctr: 0.5,
      cpc: 3000,
      revDay: 0,
      revMonth: 0,
      revYear: 0,
      clickDay: 0,
      clickMonth: 0,
      clickYear: 0
    }
  },
  created: function() {
    // console.log('created');
    this.submit();
  },
  methods: {
    submit: function() {
      click = this.pv * (this.ctr / 100);

      this.revDay = click * this.cpc;
      this.revMonth = this.revDay * 30;
      this.revYear = this.revDay * 365;

      this.clickDay = click;
      this.clickMonth = click * 30;
      this.clickYear = click * 365;
    },
    format: function(number, symbol) {
      if(symbol == undefined)
        symbol = '';

      format = '0,0';
      strNum = numeral(number).format(format);

      // console.log(strNum, symbol, symbol + 'Rp0,0');

      return symbol + strNum;
    }
  }
}).mount('#app')

};
</script>

<meta name="theme-color" content="#7952b3">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

  </head>
  <body class="bg-light">
<div class="container" id="app">
  <main>
    <div class="py-5 text-center">
      <h2>Kalkulator Google Adsense</h2>
    </div>

    <div class="row g-5">
      <div class="col-md-12 col-lg-12">
        <form class="needs-validation" novalidate v-on:submit.prevent="submit">
          <div class="row g-3">
            <div class="col-sm-12">
              <label for="firstName" class="form-label">Pageview Per Hari</label>
              <input type="text" class="form-control" id="firstName" placeholder="" v-model="pv" required>
            </div>

            <div class="col-sm-12">
              <label for="lastName" class="form-label">Click-Through Rate(CTR)</label>
              <div class="input-group has-validation">
                <input type="text" class="form-control" id="lastName" placeholder="" v-model="ctr" required>
                <span class="input-group-text">%</span>
              </div>

            </div>

            <div class="col-sm-12">
              <label for="username" class="form-label">Cost Per Click(CPC)</label>
              <div class="input-group has-validation">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="username" placeholder="" v-model="cpc" required>
                <span class="input-group-text">/klik</span>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <h4 class="mb-3">Rangkuman Perhitungan</h4>

          <div class="my-3">

<table class="table">
  <tbody>
    <tr>
      <td>Penghasilan Harian</td>
      <td class="text-end">{{ format(revDay, 'Rp') }}</td>
    </tr>
    <tr>
      <td>Penghasilan Bulanan</td>
      <td class="text-end">{{ format(revMonth, 'Rp') }}</td>
    </tr>
    <tr>
      <td>Penghasilan Tahunan</td>
      <td class="text-end">{{ format(revYear, 'Rp') }}</td>
    </tr>
    <tr>
      <td>Jumlah Klik Per Hari</td>
      <td class="text-end">{{ format(clickDay) }} klik</td>
    </tr>
    <tr>
      <td>Jumlah Klik Per Bulan</td>
      <td class="text-end">{{ format(clickMonth) }} klik</td>
    </tr>
    <tr>
      <td>Jumlah Klik Per Tahun</td>
      <td class="text-end">{{ format(clickYear) }} klik</td>
    </tr>
  </tbody>
</table>

          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit">Hitung</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="text-muted text-center text-small">
    <p class="mb-1">&copy; 2016 - 2022 <a href="<?php echo FOOTER_URL; ?>">Wevelope</a> - <?php echo FOOTER_TAGLINE; ?></p>
    <ul class="list-inline">
      <li class="list-inline-item"><a href="<?php echo ARTICLE_URL ?>">Penjelasan Cara Menghitung Google Adsense</a></li>
    </ul>
  </footer>
</div>

    <script src="../bower_components/bootstrap/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
