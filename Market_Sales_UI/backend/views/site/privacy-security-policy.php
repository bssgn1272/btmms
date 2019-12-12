<?php
/* @var $this yii\web\View */


use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Privacy and Security Policy';
?>

<div class="logo pull-left">
    <?= Html::img('@web/img/logo.png', ['style' => 'width:200px; height: 70px;']); ?>
</div>

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> <?= Html::encode($this->title) ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <p>
                    <?= Html::a('Back Home', ['site/login']) ?>
                </p>
                <p>
                 Lorem ipsum dolor sit amet, consectetur adipiscing elit. In blandit hendrerit porta. Nunc pharetra vitae leo posuere pharetra. Mauris ultricies nibh dolor, sed suscipit sapien ultrices at. Vivamus in nulla efficitur, feugiat orci sed, facilisis tortor. Duis eget mattis lacus. Maecenas hendrerit lectus a sagittis pretium. Ut ut erat orci. Curabitur euismod luctus arcu. Donec maximus, tortor et mollis tempor, felis mi tempus lorem, nec pretium tortor massa ullamcorper justo. Nam lobortis ac eros vitae hendrerit. Suspendisse in nibh finibus, aliquam orci vel, eleifend elit. Sed ac ex bibendum, tincidunt odio et, varius dolor. Curabitur dapibus ligula ut pretium ornare. Nullam pulvinar nisi augue, non pulvinar neque molestie a. Nunc in tortor sed libero porta viverra ac sed ipsum. Nullam sit amet scelerisque neque, a venenatis est.    
               </p>
                <p>
                   Pellentesque imperdiet lacus eu metus feugiat, ac ornare enim sollicitudin. Fusce sapien ex, tempor sit amet libero eget, rhoncus porttitor libero. Vestibulum mauris nibh, tempus sed enim ut, eleifend ullamcorper libero. Vestibulum pharetra, nulla eu interdum mattis, orci lorem rutrum neque, et euismod libero mauris et tortor. Nam volutpat purus eget justo ullamcorper ornare. Morbi lectus felis, congue ac vestibulum vitae, iaculis non magna. Ut fringilla et diam ut egestas. Ut sed lacinia ipsum. Maecenas est velit, pulvinar et tincidunt iaculis, malesuada id nisl. Aliquam cursus lorem a sapien condimentum dapibus. Quisque ac felis sit amet mi semper fermentum eu a purus. Phasellus ullamcorper lectus nec eros laoreet ultricies. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam quis elementum neque. Sed dictum lacus porta lectus maximus malesuada.  
               </p>
                <p>
                    Proin eget egestas felis. Praesent vestibulum dui fermentum, porta urna vel, elementum odio. Sed porta tempor porta. Vestibulum justo est, feugiat eget elit id, cursus consequat nisi. Cras aliquet cursus felis. Vivamus quis ultricies dolor, eu malesuada ipsum. Etiam congue diam id nisl porta vestibulum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer accumsan ex eu nulla mollis, sed vehicula tortor pulvinar. Nunc tincidunt felis purus, ac sodales dolor efficitur ut. Ut quis tincidunt odio. Nulla vel facilisis sem. 
               </p>
                <p>
                    Mauris in lacus id neque interdum condimentum nec eu ex. Suspendisse id dignissim tortor. Mauris consequat varius lobortis. In hac habitasse platea dictumst. Quisque pharetra cursus augue, a volutpat nisl iaculis in. Nulla efficitur pharetra quam quis tristique. Duis et ante eu odio finibus porta. 
               </p>
                <p>
                  Etiam blandit leo ac nibh fringilla, vel placerat enim aliquam. Quisque quis ultrices quam, a cursus odio. Cras pharetra vitae erat eu auctor. Vivamus venenatis varius nibh, mattis facilisis ante. Proin elementum neque vel lorem auctor cursus. Nunc in consequat nisi, nec laoreet ante. Nunc lacinia erat id posuere malesuada. In sed feugiat nunc. Nam facilisis purus ac lacus condimentum, eget elementum ligula sollicitudin.   
               </p>
                <p>
                  Ut vel feugiat enim. Praesent fermentum elementum est, et sollicitudin libero ornare volutpat. Quisque congue sem sed purus condimentum consequat. Suspendisse dictum maximus libero, sed volutpat lacus feugiat id. Quisque aliquet egestas hendrerit. Etiam ornare tortor in quam dictum vehicula. Mauris turpis nisl, viverra id interdum vel, commodo eget nibh. Ut venenatis, odio egestas laoreet egestas, arcu ante condimentum ante, ac ornare enim lacus non tellus. Aliquam aliquam molestie sodales. Etiam dapibus a ipsum eget tristique. Curabitur interdum velit et velit gravida gravida.   
               </p>
                <p>
                 Suspendisse dignissim vulputate quam in molestie. Integer bibendum vehicula justo et sagittis. Aliquam tempus magna eu scelerisque imperdiet. Suspendisse vitae ante a sapien accumsan consequat. Nunc mattis ante sed nulla semper gravida. Nam varius aliquet viverra. Vestibulum diam leo, dapibus vitae vehicula et, sodales eget ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris mattis lacus massa, sit amet semper orci lacinia vitae. Proin dapibus sollicitudin purus sed euismod. Cras volutpat tincidunt sapien vitae accumsan. Praesent ut turpis augue. Proin et sollicitudin metus, nec hendrerit est. Nullam et lorem et lacus auctor tempus. Pellentesque interdum eros id ornare aliquam.    
               </p>
               
            </div>
        </div>
    </div>
</div>

