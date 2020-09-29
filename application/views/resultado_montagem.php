
<div class="container">
    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
        <?php
        echo (isset($filtro)) ? $filtro : '';
        ?>
        <div class="filtro-vertical">
            <h3>Refinar sua busca</h3>
            <hr>
            <div class="form-group">
                <label for="oq">Eu quero:</label>
                <button class="btn btn-busca" >Comprar <span class="caret"></span></button>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <button class="btn btn-busca">Apartamento <span class="caret"></span></button>
            </div>
            <hr>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <button class="btn btn-busca">São José dos Pinhais <span class="caret"></span></button>
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <button class="btn btn-busca">Não Importa <span class="caret"></span></button>
            </div>
            <hr>
            
             <div data-role="rangeslider" >
                <label  for="_min">Valor</label>
                <input id="_min" min="'.number_format($valor->min, 0, '', '').'" max="'.number_format($valor->max, 0, '', '').'" step="'.$step.'" value="'.number_format($valor->min, 0, '', '').'" type="range">
                <label for="_max" >Valor</label>
                <input id="_max" min="'.number_format($valor->min, 0, '', '').'" max="'.number_format($valor->max, 0, '', '').'" step="'.$step.'" value="'.number_format($valor->max, 0, '', '').'" type="range">
            </div>
            <div class="valore">
                <div id="min"></div>
                <div id="max"></div>
            </div>
                            
            <div class="form-group">
                <label for="valor">Valor min: <span class="valor-range">10mil</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <div class="form-group">
                <label for="valor">Valor max: <span class="valor-range">10mil</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <hr>
            <div class="form-group">
                <label for="valor">Área terreno min: <span class="valor-range">10m&sup2;</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <div class="form-group">
                <label for="valor">Área terreno max: <span class="valor-range">10m&sup2;</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <hr>
            <div class="form-group">
                <label for="valor">Área construida min: <span class="valor-range">10m&sup2;</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <div class="form-group">
                <label for="valor">Área construida max: <span class="valor-range">10m&sup2;</span></label>
                <input type="range" step="1" min="0" max="10" >
            </div>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="quartos">Quartos</label>
                        <select class="form-control">
                            <option >1</option>
                            <option >2</option>
                            <option >3</option>
                            <option >4+</option>
                            
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="quartos">Banheiros</label>
                        <select class="form-control">
                            <option >1</option>
                            <option >2</option>
                            <option >3</option>
                            <option >4+</option>
                            
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="quartos">Vagas</label>
                        <select class="form-control">
                            <option >1</option>
                            <option >2</option>
                            <option >3</option>
                            <option >4+</option>
                            
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <h4>Caracteristicas do Imóvel: </h4>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Residencial
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Comercial
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Lazer
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Aceita Troca
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Somente com Foto
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Mobiliado
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Semi Mobiliado
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Cobertura
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Condominio fechado
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox"> Esquina
                </label>
            </div>
        </div>
    </div>
    
    <?php
    echo isset($listagem) ? $listagem : '';
    ?>
    
    <div class="col-lg-7 col-sm-7 col-md-6 col-xs-12">
        
        <div class="row publicidade">
            <h6>Publicidade</h6>
            <h3>JBA Imóveis</h3>
            <img src="http://guiasjp.com/publicidade/1383339684.jpg" >
        </div>
        
        <ul class="media-list">
            <li class="media destaque">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://www.guiasjp.com/imoveis_imagens/D1352748394.13.jpg" alt="">
                </a>
                <div class="media-body">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
                        <a href="#">
                        <p class="titulo">Apartamento</p>
                        <p><span class="glyphicon glyphicon-facetime-video"></span> | <span class="glyphicon glyphicon-map-marker"></span></p>
                        <p>Rua Seilá - 111 - <strong>Centro</strong> - São José dos Pinhais - PR</p>
                        <p><strong>650m&sup2;</strong> de área total</p>
                        <p><strong>300m&sup2;</strong> de área útil</p>
                        <p><strong>4</strong> quartos | <strong>2</strong> banheiro | <strong>2</strong> vagas </p>
                        </a>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 text-right">
                        <p class="valor_imovel">R$ 10.250.000,00</p>
                        <p>Código: 8367313</p>
                        <p>Referência: 72246.022</p>
                        <button class="btn btn-default">
                            <span class="glyphicon glyphicon-star-empty"></span> Adicionar a favoritos
                        </button>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">Contatar</button>
                            <button class="btn btn-default">Mais detalhes</button>
                        </div>
                    </div>
                </div>
            </li>
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://www.guiasjp.com/imoveis_imagens/D1352748394.13.jpg" alt="">
                </a>
                <div class="media-body">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
                        <p class="titulo">Apartamento</p>
                        <p><span class="glyphicon glyphicon-facetime-video"></span> | <span class="glyphicon glyphicon-map-marker"></span></p>
                        <p>Rua Seilá - 111 - <strong>Centro</strong> - São José dos Pinhais - PR</p>
                        <p><strong>650m&sup2;</strong> de área total</p>
                        <p><strong>300m&sup2;</strong> de área útil</p>
                        <p><strong>4</strong> quartos | <strong>2</strong> banheiro | <strong>2</strong> vagas </p>
                        
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 text-right">
                        <p class="valor_imovel">R$ 250.000,00</p>
                        <p>Código: 8367313</p>
                        <p>Referência: 72246.022</p>
                        <button class="btn btn-default">
                            <span class="glyphicon glyphicon-star-empty"></span> Adicionar a favoritos
                        </button>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">Contatar</button>
                            <button class="btn btn-default">Mais detalhes</button>
                        </div>
                    </div>
                </div>
            </li>
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://www.guiasjp.com/imoveis_imagens/D1352748394.13.jpg" alt="">
                </a>
                <div class="media-body">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
                        <p class="titulo">Apartamento</p>
                        <p><span class="glyphicon glyphicon-facetime-video"></span> | <span class="glyphicon glyphicon-map-marker"></span></p>
                        <p>Rua Seilá - 111 - <strong>Centro</strong> - São José dos Pinhais - PR</p>
                        <p><strong>650m&sup2;</strong> de área total</p>
                        <p><strong>300m&sup2;</strong> de área útil</p>
                        <p><strong>4</strong> quartos | <strong>2</strong> banheiro | <strong>2</strong> vagas </p>
                        
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 text-right">
                        <p class="valor_imovel">R$ 250.000,00</p>
                        <p>Código: 8367313</p>
                        <p>Referência: 72246.022</p>
                        <button class="btn btn-default">
                            <span class="glyphicon glyphicon-star-empty"></span> Adicionar a favoritos
                        </button>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">Contatar</button>
                            <button class="btn btn-default">Mais detalhes</button>
                        </div>
                    </div>
                </div>
            </li>
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://www.guiasjp.com/imoveis_imagens/D1352748394.13.jpg" alt="">
                </a>
                <div class="media-body">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
                        <p class="titulo">Apartamento</p>
                        <p><span class="glyphicon glyphicon-facetime-video"></span> | <span class="glyphicon glyphicon-map-marker"></span></p>
                        <p>Rua Seilá - 111 - <strong>Centro</strong> - São José dos Pinhais - PR</p>
                        <p><strong>650m&sup2;</strong> de área total</p>
                        <p><strong>300m&sup2;</strong> de área útil</p>
                        <p><strong>4</strong> quartos | <strong>2</strong> banheiro | <strong>2</strong> vagas </p>
                        
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 text-right">
                        <p class="valor_imovel">R$ 250.000,00</p>
                        <p>Código: 8367313</p>
                        <p>Referência: 72246.022</p>
                        <button class="btn btn-default">
                            <span class="glyphicon glyphicon-star-empty"></span> Adicionar a favoritos
                        </button>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">Contatar</button>
                            <button class="btn btn-default">Mais detalhes</button>
                        </div>
                    </div>
                </div>
            </li>
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://www.guiasjp.com/imoveis_imagens/D1352748394.13.jpg" alt="">
                </a>
                <div class="media-body">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
                        <p class="titulo">Apartamento</p>
                        <p><span class="glyphicon glyphicon-facetime-video"></span> | <span class="glyphicon glyphicon-map-marker"></span></p>
                        <p>Rua Seilá - 111 - <strong>Centro</strong> - São José dos Pinhais - PR</p>
                        <p><strong>650m&sup2;</strong> de área total</p>
                        <p><strong>300m&sup2;</strong> de área útil</p>
                        <p><strong>4</strong> quartos | <strong>2</strong> banheiro | <strong>2</strong> vagas </p>
                        
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6 text-right">
                        <p class="valor_imovel">R$ 250.000,00</p>
                        <p>Código: 8367313</p>
                        <p>Referência: 72246.022</p>
                        <button class="btn btn-default">
                            <span class="glyphicon glyphicon-star-empty"></span> Adicionar a favoritos
                        </button>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default">Contatar</button>
                            <button class="btn btn-default">Mais detalhes</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="pagination">
            <li><a href="#">&laquo;</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">&raquo;</a></li>
        </ul>

    </div>
    
    
    
    <div class="col-lg-2 col-sm-2 col-md-3 col-xs-12">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-vertical">
            <h6>Publicidade</h6>
            <h3>GUIASJP - Um serviço POW Internet</h3>
            <button class="btn btn-contato-email publicidade-contato" data-email="ll@pow.com.br" data-item="498">Contato por e-mail</button>
            <embed class="img-publicidade img-responsive" wmode="transparent" style="width:140px; height:140px;" data-item="498" data-url="http://www.guiasjp.com/vidacrista" allowfullscreen="false" allowscriptaccess="always" type="application/x-shockwave-flash" src="http://guiasjp.com/publicidade/1370613273.swf">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-vertical">
            <h6>Publicidade</h6>
            <h3>GUIASJP - Um serviço POW Internet</h3>
            <button class="btn btn-contato-email publicidade-contato" data-email="ll@pow.com.br" data-item="498">Contato por e-mail</button>
            <embed class="img-publicidade img-responsive" wmode="transparent" style="width:140px; height:140px;" data-item="498" data-url="http://www.guiasjp.com/vidacrista" allowfullscreen="false" allowscriptaccess="always" type="application/x-shockwave-flash" src="http://guiasjp.com/publicidade/1370613273.swf">
        </div>
    </div>
</div>