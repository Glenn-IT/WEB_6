<main>
    <div class="mb-3 pb-xl-3"></div>
    
    <!-- Search Header -->
    <section class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <h2 class="section-title text-uppercase text-center mb-3">Search <strong>Results</strong></h2>
                
                <!-- Search Form -->
                <form action="index" method="GET" class="d-flex align-items-center bg-white rounded-3 shadow-sm p-3">
                    <input type="hidden" name="page" value="search">
                    <div class="search-field flex-grow-1 me-3">
                        <input type="text" name="search" class="form-control border-0" placeholder="Search for services, items, or brand names..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7549 14.255L12.6062 11.106C13.6463 9.92231 14.253 8.38843 14.253 6.7265C14.253 3.02319 11.2298 0 7.5265 0C3.82319 0 0.799988 3.02319 0.799988 6.7265C0.799988 10.4298 3.82319 13.453 7.5265 13.453C9.18843 13.453 10.7223 12.8463 11.906 11.8062L15.0547 14.9549C15.1603 15.0605 15.3026 15.1133 15.4449 15.1133C15.5872 15.1133 15.7295 15.0605 15.8351 14.9549C16.0463 14.7437 16.0463 14.4663 15.7549 14.255ZM7.5265 11.9531C4.6508 11.9531 2.29999 9.60229 2.29999 6.7265C2.29999 3.85071 4.6508 1.5 7.5265 1.5C10.4023 1.5 12.753 3.85071 12.753 6.7265C12.753 9.60229 10.4023 11.9531 7.5265 11.9531Z" fill="white"/>
                        </svg>
                        Search
                    </button>
                </form>
                
                <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <div class="search-info mt-3 text-center">
                        <p class="text-muted mb-2">
                            Search results for: <strong>"<?= htmlspecialchars($_GET['search']) ?>"</strong>
                            <span class="badge bg-primary ms-2"><?= count($listofitems) + count($listofserveice) ?> results found</span>
                        </p>
                        <a href="index?page=search" class="btn btn-sm btn-outline-secondary">Clear Search</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
        
        <!-- Search Results -->
        <section class="container">
            <?php if(count($listofitems) > 0 || count($listofserveice) > 0): ?>
                
                <!-- Services Results -->
                <?php if(count($listofserveice) > 0): ?>
                    <div class="mb-5">
                        <h3 class="h4 mb-4">Services & Treatments</h3>
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5">
                            <?php foreach ($listofserveice as $key => $value): ?>
                                <div class="product-card-wrapper mb-3">
                                    <div class="product-card product-card_style9 border rounded-3 bg-white h-100">
                                        <div class="position-relative pb-3">
                                            <div class="pc__img-wrapper pc__img-wrapper_wide3">
                                                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                                                    <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="255" height="200" alt="<?= htmlspecialchars($value["item_name"]) ?>" class="pc__img">
                                                </a>
                                            </div>
                                            <div class="anim_appear-bottom position-absolute w-100 text-center">
                                                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>" class="btn btn-round btn-hover-red border-0 text-uppercase d-inline-flex align-items-center justify-content-center" title="View Details">
                                                    <svg class="d-inline-block" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><use href="#icon_view" /></svg>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="pc__info position-relative p-3">
                                            <p class="pc__category fs-13 fw-medium text-primary"><?=ucfirst($value["brand_name"])?></p>
                                            <h6 class="pc__title fs-16 mb-2">
                                                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>" class="text-decoration-none">
                                                    <?=ucfirst($value["item_name"])?>
                                                </a>
                                            </h6>
                                            <div class="product-card__price d-flex">
                                                <span class="money price fs-16 fw-semi-bold text-success">₱<?=number_format($value["price"],2)?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Products Results -->
                <?php if(count($listofitems) > 0): ?>
                    <div class="mb-5">
                        <h3 class="h4 mb-4">Products</h3>
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4">
                            <?php 
                            $status = [
                                "A" => '<span class="badge bg-success">Available</span>',
                                "C" => '<span class="badge bg-danger">Out of Stock</span>',
                            ];
                            foreach ($listofitems as $key => $value): 
                                $stock_status = ($value["total_in"] - $value["total_sold"]) > 0 ? 
                                    '<span class="badge bg-success">Stock: '.($value["total_in"] - $value["total_sold"]) .'</span>' : 
                                    $status["C"];
                            ?>
                                <div class="product-card-wrapper mb-4">
                                    <div class="product-card border rounded-3 bg-white h-100">
                                        <div class="pc__img-wrapper position-relative">
                                            <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                                                <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_400x541_shop"]?>" width="330" height="300" alt="<?= htmlspecialchars($value["item_name"]) ?>" class="pc__img">
                                            </a>
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <?= $stock_status ?>
                                            </div>
                                        </div>

                                        <div class="pc__info position-relative p-3">
                                            <p class="pc__category fs-13 fw-medium text-primary"><?=ucfirst($value["brand_name"])?></p>
                                            <h6 class="pc__title fs-16 mb-2">
                                                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>" class="text-decoration-none">
                                                    <?=$value["item_name"]?>
                                                </a>
                                            </h6>
                                            <div class="product-card__price d-flex justify-content-between align-items-center">
                                                <span class="money price fs-16 fw-semi-bold text-success">₱<?=number_format($value["price"],2)?></span>
                                                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>" class="btn btn-sm btn-outline-primary">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- No Results -->
                <div class="text-center py-5">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3 text-muted">
                        <path d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3S3 5.91 3 9.5S5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14Z" fill="currentColor"/>
                        <path d="M7 9H12V10H7V9Z" fill="currentColor"/>
                    </svg>
                    <h4 class="text-muted">No results found</h4>
                    <p class="text-muted mb-4">Sorry, we couldn't find any items matching your search criteria.</p>
                    <div class="mb-3">
                        <p class="mb-2">Try:</p>
                        <ul class="list-inline">
                            <li class="list-inline-item">• Using different keywords</li>
                            <li class="list-inline-item">• Checking for typos</li>
                            <li class="list-inline-item">• Using more general terms</li>
                        </ul>
                    </div>
                    <a href="index?page=shop" class="btn btn-primary me-2">Browse All Items</a>
                    <a href="index" class="btn btn-outline-secondary">Back to Home</a>
                </div>
            <?php endif; ?>
        </section>

    <?php else: ?>
        <!-- Default Search Page -->
        <section class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3 text-primary">
                        <path d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3S3 5.91 3 9.5S5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14Z" fill="currentColor"/>
                    </svg>
                    <h2 class="h3 mb-3">Search Our Services & Products</h2>
                    <p class="text-muted mb-4">Enter keywords to find the perfect services or products for your needs.</p>
                    
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Popular Searches:</h6>
                            <ul class="list-unstyled">
                                <li><a href="index?page=search&search=massage" class="text-decoration-none">Massage</a></li>
                                <li><a href="index?page=search&search=therapy" class="text-decoration-none">Therapy</a></li>
                                <li><a href="index?page=search&search=relaxation" class="text-decoration-none">Relaxation</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Browse by Category:</h6>
                            <ul class="list-unstyled">
                                <?php if(isset($header_services) && count($header_services) > 0): ?>
                                    <?php foreach ($header_services as $service): ?>
                                        <li><a href="index?page=specificshop&type=<?= urlencode($service['name']) ?>" class="text-decoration-none"><?= ucfirst($service['name']) ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><a href="index?page=shop" class="text-decoration-none">Browse All Items</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <div class="mb-5"></div>
</main>
