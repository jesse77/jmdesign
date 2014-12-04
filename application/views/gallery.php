<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                
                <div class="lead">
                    Welcome to the Gallery! You made it! Hopefully it's worth your time... If not,
                    well, I hope it doesn't come to that...
                </div>
                <div>
                    <b> *NEW* You can now order and buy prints right online! for custom prints!</b>
                </div>
                Just select hover over an image click the cart button to choose them and then click the Buy Photos button in the menu!
                <div class="break-top">Anyway it's time to ORDER AWAY!</div>
                
                <div class="divider"></div>
                <div id="plusgallery" data-userid="jessemartineau" data-type="google"></div>
                
                <div id="options">
                    
                    <ul id="filters" class="option-set clearfix" data-option-key="filter">
                        <li>
                            <a href="#filter" data-option-value="*" class="selected">All</a>
                        </li>
                        <?php foreach( $tags as $tag ): ?>
                        <li>
                            <a href="#filter" data-option-value=".<?= $tag->name ?>"><?= $tag->name ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12 portfolio-wrap">
                <div class="row">
                    <div class="portfolio">
                        <?php foreach( $photos as $photo ): ?>
                        <div class="item col-lg-3 col-md-3 col-sm-3 col-xs-12 <?= implode( $photo->tags, ' ' ) ?>">
                            <div class="portfolio-item">
                                <a class="portfolio-item-link" >
                                    <span class="portfolio-item-hover"></span>
                                    <span class="fullscreen two-icons">
                                        <i href="<?= base_url() ?>img/uploaded/<?= $photo->id ?>/large.jpg"
                                           data-rel="prettyPhoto" class="icon-search hover"></i>
                                        <i class="icon-shopping-cart red hover add-to-cart-open-modal"
                                           data-photo-id="<?= $photo->id ?>"
                                           data-toggle="modal" data-target="#add-to-cart" ></i>
                                    </span>
                                    <img src="<?= base_url() ?>img/uploaded/<?= $photo->id ?>/small.jpg"
                                         alt="<?= $photo->comment ?>"/>
                                </a>
                                <div class="portfolio-item-title">
                                    <?= $photo->title ?>
                                    <p>
                                        <?= implode( $photo->tags, ', ' ) ?>
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="divider"></div>
