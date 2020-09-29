<div class="item">
    <div class="c-content-blog-post-card-1 c-option-2">		
        <div class="c-media c-content-overlay">
            <a href="<?php echo $link;?>" target="_blank">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                            <i class="icon-link"></i>
                    </div>
                </div>
            </a>
            <img class="c-overlay-object img-responsive" src="<?php echo str_replace('-192x192', '', $imagem)?>" alt="">
        </div>
        <div class="c-body">
            <div class="c-title c-font-uppercase c-font-bold">
                <a href="<?php echo $link;?>" target="_blank"><?php echo $title;?></a>
            </div>
            <div class="c-author">
                <span class="c-font-uppercase"><?php echo $data;?></span>
            </div>
            <p>
               <?php 
               
               echo $descricao;?>
            </p>
        </div>
    </div>
</div>