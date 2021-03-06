<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\BannerDonasi;
use backend\models\ProgramDonasi;
use backend\models\DonasiTemp;
use backend\models\Donasi;

$idprogram = $_GET['program'];
$idinvoice = $_GET['invoice'];

$donasitemp = DonasiTemp::find()
    ->where(['id_invoice'=>$idinvoice])
    ->orderBy(['id' => SORT_DESC])
    ->one();

$donasi = Donasi::find()
    ->where(['id_invoice'=>$idinvoice])
    ->orderBy(['id' => SORT_DESC])
    ->one();
$banner = BannerDonasi::find()
    ->where(['program_id' => $idprogram ])
    //->groupBy('userid')
    ->orderBy(['id' => SORT_DESC])
    ->one();

$program = ProgramDonasi::find()
    ->where(['id' => $idprogram ])
    //->groupBy('userid')
    ->orderBy(['id' => SORT_DESC])
    ->one();
?>

<?php
  if (empty($donasi)){
?>
  <div class="row justify-content-md-center">
    <div class="col col-md-5 card-body shadow">
      <div>
        <center>
          <?= Html::img('@imageurl/admingesit/banner/'.$banner->banner,['width' => '200'],['class' => 'img-responsive'])?>
          <h3 class="pt-2"><?= $program->title?></h3>
        </center>
      </div>
        
      <hr>
          <p>
            Nominal Donasi : <b> Rp <?=  $european_format_number = number_format($donasitemp->jumlah); ?></b><br>
            Nama Donatur : <?= $donasitemp->nama?><br>
            Email : <?= $donasitemp->email?>
          </p>

        <button id="pay-button" class="btn btn-primary btn-block">Lanjut Pembayaran</button>
            <!-- <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre>  -->
    </div>
  </div>
  <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-hm24ssPLoHtjIq6Z"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('<?=$snapToken?>', {
          // Optional
          onSuccess: function(result){
            // /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            let dataResult = JSON.stringify(result, null, 2);
            let dataObj = JSON.parse(dataResult);

            $.ajax({
              type: "POST",
              url: "/gesit/site/finish",
              data: {
                idprogram:<?= $idprogram?>,
                invoice:'<?= $idinvoice?>',
                name:'<?= $donasitemp->nama?>',
                email:'<?= $donasitemp->email?>',
                pesan:'<?= $donasitemp->pesan?>',
                jumlah:'<?= $donasitemp->jumlah?>',
                pdf_url:dataObj.pdf_url,
                order_id:dataObj.order_id,
                payment_type:dataObj.payment_type,
                transaction_time: dataObj.transaction_time,
                transaction_status: dataObj.transaction_status

              },
              dataType:"json",
              success: function(response){
                if (response.sukses){
                  alert(response.sukses);
                  window.location.reload();
                }
              }
            })
            console.log(JSON.stringify(result, null, 2));
          },
          // Optional
          onPending: function(result){
            // /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            let dataResult = JSON.stringify(result, null, 2);
            let dataObj = JSON.parse(dataResult);

            $.ajax({
              type: "POST",
              url: "/gesit/site/finish",
              data: {
                idprogram:<?= $idprogram?>,
                invoice:'<?= $idinvoice?>',
                name:'<?= $donasitemp->nama?>',
                email:'<?= $donasitemp->email?>',
                pesan:'<?= $donasitemp->pesan?>',
                jumlah:'<?= $donasitemp->jumlah?>',
                pdf_url:dataObj.pdf_url,
                order_id:dataObj.order_id,
                payment_type:dataObj.payment_type,
                transaction_time: dataObj.transaction_time,
                transaction_status: dataObj.transaction_status

              },
              dataType:"json",
              success: function(response){
                if (response.sukses){
                  alert(response.sukses);
                  window.location.reload();
                }
              }
            })
            console.log(JSON.stringify(result, null, 2));
          },
          // Optional
          onError: function(result){
            // /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            let dataResult = JSON.stringify(result, null, 2);
            let dataObj = JSON.parse(dataResult);

            $.ajax({
              type: "POST",
              url: "/gesit/site/finish",
              data: {
                idprogram:<?= $idprogram?>,
                invoice:'<?= $idinvoice?>',
                name:'<?= $donasitemp->nama?>',
                email:'<?= $donasitemp->email?>',
                pesan:'<?= $donasitemp->pesan?>',
                jumlah:'<?= $donasitemp->jumlah?>',
                pdf_url:dataObj.pdf_url,
                order_id:dataObj.order_id,
                payment_type:dataObj.payment_type,
                transaction_time: dataObj.transaction_time,
                transaction_status: dataObj.transaction_status

              },
              dataType:"json",
              success: function(response){
                if (response.sukses){
                  alert(response.sukses);
                  window.location.reload();
                }
              }
            })
            console.log(JSON.stringify(result, null, 2));
          }
        });
      };
    </script>
<?php } else { ?>

  <div class="row justify-content-md-center">
    <div class="col col-md-5 card-body shadow">
      <center>
        <?= Html::img('@imageurl/img/donasi-01.png',['width' => '330'],['class' => 'img-responsive'])?>
        <p>Terimakasih sudah berdonasi. Silakan cek email anda dan selesaikan pembayaran</p>
        
        <p>atau unduh panduan pembayaran dibawah ini</p>
        
        <a href="<?= $donasi->pdf_url?>" class="btn btn-primary" target="_blank" role="button" aria-pressed="true">Download Intruksi Pembayaran</a>
        
      </center>
    </div>
  </div>
  
<?php } ?>