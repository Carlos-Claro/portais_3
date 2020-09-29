<?php 
echo $form;
/**


<div class="container-fluid main" data-pesquisa="<?php echo $pesquisa;?>" data-uri="<?php echo $uri;?>">
    <div class="c-layout-sidebar-menu c-theme ">
        <ul class="c-shop-filter-search-1 list-unstyled">
            <li>
                <label class="control-label c-font-uppercase c-font-bold">Localidade</label>
                <select class="form-control c-square c-theme">
                    <option value="0"></option>
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                </select>
                <span class="help-block">
                    <span class="label label-primary"><span>x</span> Curitiba</span>
                    <span class="label label-primary"><span>x</span> Água Verde</span>
                </span>
            </li>
            <li>
                <label class="control-label c-font-uppercase c-font-bold">Tipo do imóvel</label>
                <select class="form-control c-square c-theme">
                    <option value="0">Todos</option>
                    <option value="1">Apartamentos</option>
                    <option value="2">Casas</option>
                    <option value="3">Sobrados</option>
                </select>
            </li>
            <li class="c-margin-b-40">
                <label class="control-label c-font-uppercase c-font-bold">Quartos</label>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-1" class="c-check"> <label for="checkbox-sidebar-2-1">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> (1)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-2" class="c-check"> <label for="checkbox-sidebar-2-2">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> (2)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-3" class="c-check"> <label for="checkbox-sidebar-2-3">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> (3)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-4" class="c-check"> <label for="checkbox-sidebar-2-4">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> (4+)
                        </p>
                    </label>
                </div>
                
            </li>
            <li class="c-margin-b-40">
                <label class="control-label c-font-uppercase c-font-bold">Vagas</label>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-1" class="c-check"> <label for="checkbox-sidebar-2-1">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> (1)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-2" class="c-check"> <label for="checkbox-sidebar-2-2">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> (2)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-3" class="c-check"> <label for="checkbox-sidebar-2-3">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span>
                            <span class="fa fa-star c-theme-font"></span> (3)
                        </p>
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="checkbox-sidebar-2-4" class="c-check"> <label for="checkbox-sidebar-2-4">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        <p class="c-review-star">
                            <span class="fa fa-star c-theme-font"></span> <span class="fa fa-star c-theme-font"></span> (4+)
                        </p>
                    </label>
                </div>
                
            </li>
        </ul>
    </div>
    <div class="c-layout-sidebar-content">
        <div class="c-shop-result-filter-1 clearfix form-inline">
            <div class="clearfix">
                <ul class="nav nav-tabs c-font-uppercase c-font-bold">
                    <li class="tipo-negocio active" data-tipo="comprar"><a href="#" data-toggle="tab">Comprar</a></li>
                    <li class="tipo-negocio" data-tipo="alugar"><a href="#"  data-toggle="tab">Alugar</a></li>
                    <li class="tipo-negocio" data-tipo="alugar_dia"><a href="#"  data-toggle="tab">Alugar Dia</a></li>
                </ul>
            </div>
            <div class="c-filter">
                <label class="control-label c-font-16">Sort&nbsp;By:</label>
                <select class="form-control c-square c-theme c-input">
                    <option value="#?sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
                    <option value="#?sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                    <option value="#?sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                    <option value="#?sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                    <option value="#?sort=p.price&amp;order=DESC" selected>Price (High &gt; Low)</option>
                    <option value="#?sort=rating&amp;order=DESC">Rating (Highest)</option>
                    <option value="#?sort=rating&amp;order=ASC">Rating (Lowest)</option>
                    <option value="#?sort=p.model&amp;order=ASC">Model (A - Z)</option>
                    <option value="#?sort=p.model&amp;order=DESC">Model (Z - A)</option>
                </select>
            </div>
        </div>
        
        <div class="c-margin-t-20"></div>
        <?php for ($a=0;$a<10;$a++):?>
        <div class="row c-margin-b-40">
            <div class="c-content-product-2 c-bg-white">
                <div class="col-md-4">
                    <div class="c-content-overlay">
                        <div class="c-label c-bg-red c-font-uppercase c-font-white c-font-13 c-font-bold">Venda</div>
                        <div class="c-label c-bg-red c-font-uppercase c-font-white c-font-13 c-font-bold">Venda</div>
                        <div class="c-overlay-wrapper">
                            <div class="c-overlay-content">
                                <a href="shop-product-details.html" class="btn btn-md c-btn-grey-1 c-btn-uppercase c-btn-bold c-btn-border-1x c-btn-square">Veja mais</a>
                            </div>
                        </div>
                        <div class="c-bg-img-center c-overlay-object" data-height="height" style="height: 230px; background-image: url(../../assets/base/img/content/shop3/20.jpg);"></div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="c-info-list">
                        <h3 class="c-title c-font-bold c-font-22 c-font-dark">
                            <a class="c-theme-link" href="shop-product-details.html">Titulo</a>
                        </h3>
                        <p class="c-desc c-font-16 c-font-thin">descrição</p>
                        <p class="c-price c-font-26 c-font-thin">R$548 &nbsp;
<!--                            <span class="c-font-26 c-font-line-through c-font-red">R$600</span>-->
                        </p>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-sm c-theme-btn c-btn-square c-btn-uppercase c-btn-bold">
                            <i class="fa fa-shopping-cart"></i>Favoritar/comparar
                        </button>
                        <button type="submit" class="btn btn-sm btn-default c-btn-square c-btn-uppercase c-btn-bold">
                            <i class="fa fa-heart-o"></i>Verificar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endfor;?>
        
    </div>
</div>
 * 
 */