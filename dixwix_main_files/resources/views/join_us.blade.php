@include('common.wo_login.header')
@include('common.wo_login.start_scripts')
@include('common.wo_login.end_scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  
  .dixpricing .content li::before, .career-about li::before{
    display: none;
  }
  
  li.no-before::before {
    content: none !important;
    background: none !important;
}
  
  .fa-list li::before {
    content: none !important;
    background: none !important;
}

  
  .dixpricing .content li {
    position: relative;
    padding-left: 24px;
}

li i.fa + span::before {
    content: none !important;
    background: none !important;
}

  


.dixpricing .content li.cross::before {
    background-image: url('../img/cross.png');
    background-repeat: no-repeat;
    content: "";
    display: block;
    height: 16px;
    left: 0;
    position: absolute;
    top: 4px;
    width: 16px;
}

</style>

<section class="<?=(isset($data['background-class']) && !empty($data['background-class']))?$data['background-class']:"heading"?>" id="box">
    <div class="text-box">
        <h1 style="color: #D94E29">{!! $data['banner_heading'] !!}</h1>
      	<p class="text-dark">Start sharing and renting your items within a trusted community. Create<br>
                              your online catalog and enjoy the benefits of DixWix.com — a platform<br>
                              that makes sharing easy, secure, and rewarding. </p>
        <?php if(isset($data['is_banner_link']) && $data['is_banner_link']) { ?>
        <div class="bannersecbtn">
            <a href="{{$data['banner_link']}}">{{$data['banner_text']}}</a>
        </div>
        <?php } else { ?> <p>{{$data['banner_text']}}</p> <?php } ?>
    </div>
</section>

<div class="container-fluid intro-wrapper position-relative">
    <img src="img/isolationimage.png" class="imagepattren">
    <div class="container intro-section text-center">
        <div class="row">
            <div class="col-md-12 bgimge mt-5">

			 <div class="head text-center">
            <p>At DixWix.com, we believe in the power of community, collaboration and sustainability. Our platform enables you to share resources, skills and expertise with friends and neighbors. Renting and borrowing resources within trusted groups, we reduce waste, promote sustainability, while earning rewards for contributing to the community</p>
        </div>
              <h1>Together, let's build a greener future in a<br> collaborative, secure and friendly environment”</h1>
            </div>
        </div>
    </div>
</div>


<section class="dixpricing">
    <div class="container">
        <div class="head text-center">
            <h2>Simple, Transparent <span>Pricing</span></h2>
            <div class="col-lg-8 col-md-12 mx-auto">
            <p>We don’t sell customer data or ad’s. Our pricing is based on a shared cost model designed to fit everyone’s needs. Everyone can get started for free with an option to upgrade for a larger number of items.</p>
            </div>
        </div>
        <div class="list row d-flex justify-content-center">
            @foreach($plans as $plan)  
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="content">
                        <p>{{ $plan->name }}</p>
                        <p class="price">
                            @if($plan->price == 0)
                                Free
                            @else
                                ${{ $plan->price }}/Month
                            @endif
                        </p>
                        <ul>
    <li><i class="fa-solid fa-check text-success me-1"></i>{{ $plan->allowed_items }} Items</li>
    <li><i class="fa-solid fa-check text-success me-1"></i>{{ $plan->allowed_groups }} Groups</li>

    @if(!empty($plan->FixedCategories))
        <li><i class="fa-solid fa-check text-success me-1"></i>Fixed Categories: {{ $plan->FixedCategories }}</li>
    @endif

    <li>
        <i class="fa-solid {{ $plan->LendBorrowincluded ? 'fa-check text-success' : 'fa-xmark text-danger' }} me-1"></i>
        Lend / Borrow Included
    </li>
    <li>
        <i class="fa-solid {{ $plan->qr ? 'fa-check text-success' : 'fa-xmark text-danger' }} me-1"></i>
        QR Codes Included
    </li>
    <li>
        <i class="fa-solid {{ $plan->reward ? 'fa-check text-success' : 'fa-xmark text-danger' }} me-1"></i>
        Rewards Included
    </li>
    <li>
        <i class="fa-solid {{ $plan->google ? 'fa-check text-success' : 'fa-xmark text-danger' }} me-1"></i>
        Google SSO Included
    </li>
    <li>
        <i class="fa-solid {{ $plan->notification ? 'fa-check text-success' : 'fa-xmark text-danger' }} me-1"></i>
        Notifications
    </li>
</ul>


                        <a href="">Add to Cart</a>
                    </div>
                </div>
            @endforeach
           
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dixpricing .content li').forEach(function (li) {
            if (li.querySelector('i.fa')) {
                li.style.setProperty('--before-display', 'none');
                li.style.position = 'relative'; // Ensure no layout shift
            }
        });
    });
</script>



<!-- Services End -->

 @include('common.wo_login.footer') <!-- Dynamic footer section -->

    <!-- Include core JavaScript files -->
    <script src="{{ asset('js/main.js') }}"></script>  <!-- Your main JavaScript file -->
