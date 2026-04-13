<?php
// Extract all variables returned by CustomerController::about()
// They are passed through $custome (set in index() via $data["custome"])
if (isset($custome) && is_array($custome)) {
    extract($custome);
}
// Ensure defaults so the view never breaks
if (!isset($about_sections))  $about_sections  = [];
if (!isset($contact_info))    $contact_info    = [];
if (!isset($therapists))      $therapists      = [];
if (!isset($team_photo))      $team_photo      = null;
if (!isset($developers))      $developers      = [];
?>
<main>


   <section class="swiper-container js-swiper-slider slideshow full-width_padding">
      <div class="swiper-wrapper">
        <div class="swiper-slide full-width_border border-1" style="border-color: #f5e6e0;">
          <div class="overflow-hidden position-relative h-100">
            <div class="slideshow-bg" style="background-color: #f5e6e0;">
              <img loading="lazy" src="<?=$_ENV["URL_HOST"]?>src/images/logos/background.jpg" width="1761" height="778" alt="Pattern" class="slideshow-bg__img object-fit-cover" style="opacity: 0.8;" >
            </div>
            <!-- <p class="slideshow_markup font-special text-uppercase position-absolute end-0 bottom-0">Summer</p> -->
           
           
          </div>
        </div><!-- /.slideshow-item -->


      </div><!-- /.slideshow-wrapper js-swiper-slider -->

      <div class="container">
        <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-5"></div>
        <!-- /.products-pagination -->
      </div><!-- /.container -->

     
    </section><!-- /.slideshow -->


    <div class="mb-4 pb-4"></div>
    <section class="about-us container">
      <div class="mw-930">
        <h2 class="page-title " style="text-align: center;">
          <?= isset($about_sections['main_title']) ? htmlspecialchars($about_sections['main_title']) : 'Touch and Care Massage and Spa' ?>
        </h2>
      </div>
      <div class="about-us__content pb-5 mb-5">
        <div class="mw-930">
          <?php
          $paragraphKeys = ['paragraph_1','paragraph_2','paragraph_3','paragraph_4'];
          $paragraphDefaults = [
            'paragraph_1' => 'At Touch and Care Massage and Spa, we believe that healing starts with the power of touch and the warmth of genuine care. Our spa is a peaceful sanctuary dedicated to helping you relax, recharge, and restore your mind and body.',
            'paragraph_2' => 'Founded with a passion for wellness and holistic beauty, we offer a wide range of services—from Swedish, Shiatsu, hot stone, ventosa, and therapeutic massages to nail care, skin care, and hair treatment.',
            'paragraph_3' => 'We don\'t just offer massages—we create a full self-care experience.',
            'paragraph_4' => 'Come and experience the perfect harmony of touch and care—where your beauty and relaxation are always our priority.',
          ];
          foreach ($paragraphKeys as $pKey):
            $pText = isset($about_sections[$pKey]) && !empty($about_sections[$pKey])
                       ? $about_sections[$pKey] : ($paragraphDefaults[$pKey] ?? '');
            if (!empty($pText)):
          ?>
          <p class="fs-6 fw-medium mb-4"><?= htmlspecialchars($pText) ?></p>
          <?php endif; endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ===== WHO WE ARE — Therapist Team Section ===== -->
    <section class="who-we-are-section py-5">
      <div class="container">

        <!-- Section Header -->
        <div class="row">
          <div class="col-12 text-center mb-5">
            <h2 class="section-title">Who We Are</h2>
            <p class="fs-6 text-muted">Meet our skilled and certified team of therapists dedicated to your wellness</p>
            <div class="title-underline mx-auto" style="width:60px;height:3px;background:linear-gradient(135deg,#c8956c,#e8b89a);border-radius:2px;margin-top:0.75rem;"></div>
          </div>
        </div>

        <?php if (!empty($therapists)): ?>

          <!-- Group / Team Photo -->
          <?php if (!empty($team_photo)): ?>
          <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
              <img src="<?= $_ENV['URL_HOST'] ?><?= htmlspecialchars($team_photo['image_path']) ?>"
                   class="img-fluid rounded shadow"
                   style="max-height:380px;width:100%;object-fit:cover;"
                   alt="<?= htmlspecialchars($team_photo['caption'] ?? 'Our Team') ?>">
              <?php if (!empty($team_photo['caption'])): ?>
              <h2 class="section-title mt-4"><?= htmlspecialchars($team_photo['caption']) ?></h2>
              <div class="title-underline mx-auto" style="width:60px;height:3px;background:linear-gradient(135deg,#c8956c,#e8b89a);border-radius:2px;margin-top:0.75rem;"></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Individual Therapist Cards -->
          <div class="row justify-content-center">
            <?php foreach ($therapists as $therapist): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="therapist-card text-center h-100">
                <div class="therapist-photo-wrap mx-auto mb-3">
                  <?php if (!empty($therapist['photo'])): ?>
                    <img src="<?= $_ENV['URL_HOST'] ?><?= htmlspecialchars($therapist['photo']) ?>"
                         class="therapist-photo"
                         alt="<?= htmlspecialchars($therapist['name']) ?>">
                  <?php else: ?>
                    <div class="therapist-photo-placeholder">
                      <i class="fa fa-user"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <h5 class="therapist-name mb-1"><?= htmlspecialchars($therapist['name']) ?></h5>
                <?php if (!empty($therapist['position'])): ?>
                  <p class="therapist-position mb-1"><?= htmlspecialchars($therapist['position']) ?></p>
                <?php endif; ?>
                <?php if (!empty($therapist['ser_type'])): ?>
                  <span class="therapist-badge"><?= htmlspecialchars($therapist['ser_type']) ?></span>
                <?php endif; ?>
                <?php if (!empty($therapist['bio'])): ?>
                  <p class="therapist-bio mt-2"><?= htmlspecialchars($therapist['bio']) ?></p>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

        <?php else: ?>
          <!-- No therapists yet -->
          <div class="row justify-content-center">
            <div class="col-md-6 text-center text-muted py-5">
              <i class="fa fa-users fa-3x mb-3" style="color:#c8956c;opacity:.6;"></i>
              <p>Our team profiles will be available soon. Stay tuned!</p>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </section>

    <!-- "Who We Are" section styles -->
    <style>
      .who-we-are-section { background: #fff; }
      .therapist-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem 1rem;
        box-shadow: 0 4px 18px rgba(0,0,0,.07);
        transition: transform .3s ease, box-shadow .3s ease;
      }
      .therapist-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 28px rgba(0,0,0,.13);
      }
      .therapist-photo-wrap { width:110px;height:110px; }
      .therapist-photo {
        width:110px;height:110px;
        border-radius:50%;
        object-fit:cover;
        border:3px solid #c8956c;
      }
      .therapist-photo-placeholder {
        width:110px;height:110px;
        border-radius:50%;
        background:#f5e6df;
        border:3px solid #c8956c;
        display:flex;align-items:center;justify-content:center;
        font-size:2.5rem;color:#c8956c;
      }
      .therapist-name { font-weight:700;color:#2c3e50;font-size:1rem; }
      .therapist-position { color:#c8956c;font-size:.85rem;font-weight:600; }
      .therapist-badge {
        display:inline-block;
        background:#f5e6df;
        color:#c8956c;
        font-size:.75rem;
        font-weight:600;
        padding:.2rem .7rem;
        border-radius:20px;
        margin-bottom:.25rem;
      }
      .therapist-bio { font-size:.82rem;color:#666;line-height:1.5; }
    </style>

    <!-- Contact Section -->
    <section class="contact-section bg-light py-5">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center mb-5">
            <h2 class="section-title">Contact Us</h2>
            <p class="fs-6 text-muted">Get in touch with us for appointments and inquiries</p>
          </div>
        </div>
        
        <div class="row">
          <!-- Contact Information -->
          <div class="col-lg-6 mb-4">
            <div class="contact-info">
              <h4 class="mb-4">Contact Information</h4>
              
              <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                  <i class="fas fa-map-marker-alt text-primary fs-5"></i>
                </div>
                <div>
                  <h6 class="mb-1">Address</h6>
                  <p class="mb-0 text-muted"><?= isset($contact_info) ? $contact_info['address'] : 'JPF7+M72, Diversion Road, Tuguegarao City, Cagayan' ?></p>
                </div>
              </div>
              
              <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                  <i class="fas fa-phone text-primary fs-5"></i>
                </div>
                <div>
                  <h6 class="mb-1">Phone</h6>
                  <p class="mb-0 text-muted">
                    <a href="tel:<?= isset($contact_info) ? $contact_info['phone'] : '09356724821' ?>" class="text-decoration-none">
                      <?= isset($contact_info) ? $contact_info['phone'] : '09356724821' ?>
                    </a>
                  </p>
                </div>
              </div>
              
              <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                  <i class="fas fa-envelope text-primary fs-5"></i>
                </div>
                <div>
                  <h6 class="mb-1">Email</h6>
                  <p class="mb-0 text-muted">
                    <a href="mailto:<?= isset($contact_info) ? $contact_info['email'] : 'touchandcaremassageandspa@gmail.com' ?>" class="text-decoration-none">
                      <?= isset($contact_info) ? $contact_info['email'] : 'touchandcaremassageandspa@gmail.com' ?>
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- Map -->
          <div class="col-lg-6 mb-4">
            <div class="map-container">
              <h4 class="mb-4">Find Us</h4>
              <div class="embed-responsive embed-responsive-16by9" style="height: 300px;">
                <?php
                $mapSrc = isset($contact_info['map_embed']) && !empty($contact_info['map_embed'])
                    ? $contact_info['map_embed']
                    : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3827.123456789!2d121.730000000!3d17.625000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sJPF7%2BM72%2C%20Diversion%20Road!5e0!3m2!1sen!2sph!4v16970000000000';
                ?>
                <iframe
                  src="<?= htmlspecialchars($mapSrc) ?>"
                  width="100%"
                  height="100%"
                  style="border:0;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  class="rounded">
                </iframe>
              </div>
              <p class="text-muted mt-2 small">
                <i class="fas fa-info-circle me-1"></i>
                Located along Diversion Road for easy access
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Developers Section -->
    <section class="developers-section py-5">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center mb-5">
            <h2 class="section-title">Development Team</h2>
            <p class="fs-6 text-muted">Meet the talented developers behind this platform</p>
          </div>
        </div>
        
        <div class="row justify-content-center">
          <?php if(isset($developers)): ?>
            <?php foreach($developers as $developer): ?>
              <div class="col-lg-5 col-md-6 mb-4">
                <div class="developer-card bg-white p-4 rounded shadow-sm h-100">
                  <div class="developer-info text-center">
                    <div class="developer-avatar mb-3">
                      <img src="<?php echo isset($developer['name']) && $developer['name'] === 'Maricris Gaducio' ? $_ENV['URL_HOST'].'devImage/Gaducio.jpg' : $_ENV['URL_HOST'].'devImage/Managan.jpg'; ?>" 
                           alt="Developer Avatar" 
                           class="rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                           style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    
                    <h5 class="developer-name mb-2"><?= htmlspecialchars($developer['name']) ?></h5>
                    
                    <div class="developer-contacts">
                      <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                        <i class="fas fa-mobile-alt text-primary me-2"></i>
                        <a href="tel:<?= htmlspecialchars($developer['contact']) ?>" class="text-decoration-none text-muted">
                          <?= htmlspecialchars($developer['contact']) ?>
                        </a>
                      </div>
                      
                      <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <a href="mailto:<?= htmlspecialchars($developer['email']) ?>" class="text-decoration-none text-muted">
                          <?= htmlspecialchars($developer['email']) ?>
                        </a>
                      </div>
                      
                      <div class="contact-item d-flex justify-content-center align-items-center">
                        <i class="fab fa-facebook text-primary me-2"></i>
                        <a href="<?= htmlspecialchars($developer['facebook']) ?>" target="_blank" class="text-decoration-none text-muted">
                          Facebook Profile
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Fallback developer information -->
            <div class="col-lg-5 col-md-6 mb-4">
              <div class="developer-card bg-white p-4 rounded shadow-sm h-100">
                <div class="developer-info text-center">
                  <div class="developer-avatar mb-3">
                    <img src="<?=$_ENV['URL_HOST']?>devImage/Managan.jpg" 
                         alt="Developer Avatar" 
                         class="rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                  </div>
                  
                  <h5 class="developer-name mb-2">Jude Manangan</h5>
                  
                  <div class="developer-contacts">
                    <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                      <i class="fas fa-mobile-alt text-primary me-2"></i>
                      <a href="tel:09157609077" class="text-decoration-none text-muted">09157609077</a>
                    </div>
                    
                    <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                      <i class="fas fa-envelope text-primary me-2"></i>
                      <a href="mailto:judegmanangan13@gmail.com" class="text-decoration-none text-muted">judegmanangan13@gmail.com</a>
                    </div>
                    
                    <div class="contact-item d-flex justify-content-center align-items-center">
                      <i class="fab fa-facebook text-primary me-2"></i>
                      <a href="https://www.facebook.com/share/16D3GofTni/" target="_blank" class="text-decoration-none text-muted">Facebook Profile</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-5 col-md-6 mb-4">
              <div class="developer-card bg-white p-4 rounded shadow-sm h-100">
                <div class="developer-info text-center">
                  <div class="developer-avatar mb-3">
                    <img src="<?=$_ENV['URL_HOST']?>devImage/Gaducio.jpg" 
                         alt="Developer Avatar" 
                         class="rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                  </div>
                  
                  <h5 class="developer-name mb-2">Maricris Gaducio</h5>
                  
                  <div class="developer-contacts">
                    <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                      <i class="fas fa-mobile-alt text-primary me-2"></i>
                      <a href="tel:09550272754" class="text-decoration-none text-muted">09550272754</a>
                    </div>
                    
                    <div class="contact-item d-flex justify-content-center align-items-center mb-2">
                      <i class="fas fa-envelope text-primary me-2"></i>
                      <a href="mailto:mgaducio@gmail.com" class="text-decoration-none text-muted">mgaducio@gmail.com</a>
                    </div>
                    
                    <div class="contact-item d-flex justify-content-center align-items-center">
                      <i class="fab fa-facebook text-primary me-2"></i>
                      <a href="https://www.facebook.com/share/1EPa2cXCRU/" target="_blank" class="text-decoration-none text-muted">Facebook Profile</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    
    <style>
      .contact-section {
        background-color: #f8f9fa;
      }
      
      .section-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
      }
      
      .contact-item {
        padding: 0.5rem 0;
      }
      
      .contact-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(0, 123, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .developer-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e9ecef;
      }
      
      .developer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
      }
      
      .avatar-placeholder {
        font-size: 2rem;
      }
      
      .developer-name {
        color: #2c3e50;
        font-weight: 600;
      }
      
      .developer-contacts a {
        transition: color 0.3s ease;
      }
      
      .developer-contacts a:hover {
        color: #007bff !important;
      }
      
      .map-container iframe {
        border-radius: 8px;
      }
      
      @media (max-width: 768px) {
        .contact-item {
          flex-direction: column;
          text-align: center;
        }
        
        .contact-icon {
          margin-bottom: 0.5rem;
        }
      }
    </style>
    
     
  
        <div class="mb-5 pb-xl-5"></div>


  </main>