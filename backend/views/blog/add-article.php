<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加文章';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '添加文章';
$this->params['three_menu'] = '文章管理';

?>

<link rel="stylesheet" type="text/css" href="/statics/css/upload.css">
<script type="text/javascript" src="/js/SimpleHtmlTree.js"></script>
<div class="content">
    <h4>添加文章</h4>
    <div class="container">
                <div class="row">
    			<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']]); ?>
    			<div id="uploads" class="form-group ">
				<div class="form-group field-articleback-img">
					<label class="control-label" for="articleback-img">封面图</label>
					<div class="per_upload_con">
						<div class="per_real_img img">
						<?php if($isNew == "true"){?>
							<img src="http://img.hxinq.com/article/upload.png" class="img_image" onClick="uploadFile();" style="background:#f2f2f2;">
						<?php }else{ ?>
							<img src="<?=$staticUrl.$model->img?>" class="img_image">
						<?php } ?>
						</div>
    						<div class="per_upload_img" style="">图片上传</div>
    						<div class="per_upload_text">
			        			<p class="upbtn"><a id="img" href="javascript:;" class="btn btn-success green choose_btn" onClick="uploadFile();">选择图片</a></p>
        						<p class="rule">仅支持文件格式为jpg、jpeg、png<br>大小在5MB以下的文件</p>
			    			</div>
						<?php if($isNew == "true"){?>
							<input type="hidden" id="img_image" name="ArticleBack[img]">
						<?php }else{ ?>
							<input type="hidden" id="img_image" name="ArticleBack[img]" value="<?=$model->img?>">
						<?php } ?>
					</div>
				</div>
				<?=$form->field($model,'title');?>
				<?=$form->field($model,'desc')->widget('\yidashi\markdown\Markdown',['name'=>'desc'])?>
				<!--<?=$form->field($model,'desc')->widget('\yidashi\markdown\Markdown',['name'=>'desc','options'=>['url'=>'/blog/upload']])?>-->
				<?=$form->field($model,'cid')->widget('\\kartik\\select2\\Select2',['data'=>$FirstCate,'options'=>['placeholder'=>'请选择分类'],'pluginOptions'=>['allowClear'=>true]])?>
				<!--<?=$form->field($model,'tid')->widget('\\kartik\\select2\\Select2',['data'=>$Tags,'options'=>['multiple'=>true,'placeholder'=>'请选择标签'],'pluginOptions'=>['allowClear'=>true]])?>--><!--multiple多选-->
				<?=$form->field($model,'tid')->widget('\\kartik\\select2\\Select2',['data'=>$Tags,'options'=>['placeholder'=>'请选择标签'],'pluginOptions'=>['allowClear'=>true]])?>
				<?=$form->field($model,'weight');?>
				<?=$form->field($model,'is_recommend')->widget('kartik\widgets\SwitchInput');?>
			</div>
    			<div class="form-group ">
				<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
			</div>
    			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>

<script>

        function uploadFile() {
            $(".content").append("<div id='newlayer' class=\"overlay\"></div>");
            $(".content").append("" +
            "<div id='newwindow' class=\"newwindow\">" +
            "<div id='close' class='close'>X</div>" +
            "<div class='caption'>" +
            "<h5>请选择上传的图</h5>" +
            "<form id=\"uploadFrom\" action=\"/blog/upload-lable\" method=\"post\" target=\"tarframe\" enctype=\"multipart/form-data\">" +
            "<input type=\"file\" id=\"upload_file\" name=\"upload\">" +
            "<input type='submit' id='submitId' value='上传'  class='btn btn-large btn-primary'>" +
            "</form>" +
            "<iframe src='' width='0' height='0' style=\"display:none;\" name=\"tarframe\">" +
            "</iframe>" +
            "</div>" +
            "</div>");
            $("#close").click(function () {
                $("#newlayer").remove();
                $("#newwindow").remove();
            });
        }

        $(function () {
            $("#upload_file").change(function () {
                $("#uploadFrom").submit();
            });
        });

        function stopSend( img, img_path) {
            $("#newlayer").remove();
            $("#newwindow").remove();

            $("#img_image").val(img_path);
            $(".img_image").attr("src", img);
        }
</script>

