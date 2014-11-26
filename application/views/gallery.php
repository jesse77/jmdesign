<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                
                Welcome to the Gallery! You made it! Hopefully it's worth your time... If not, well,
                I hope it doesn't come to that... </br></br> *NEW* You may now submit an order for a
                custom print, by me !</br> Just fill out the form and I will contact you myself! So
                really thats a double win for you... Or a double loss and my double win...
                <br/><br/>Anyway it's time to ORDER AWAY!
                <a href="https://docs.google.com/forms/d/1V1HRH1vYxKPpM2JQ5_iu4XKyS7aD2Qt4VatOzWVSZ7k/viewform"
                   target="_blank"  > Fill out this form! </a>
                
                
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
                                        <i class="icon-shopping-cart red"
                                           data-photo-id="<?= $photo->id ?>"
                                           data-action="add-to-cart" ></i>
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
