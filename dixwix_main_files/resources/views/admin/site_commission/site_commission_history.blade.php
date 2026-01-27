<style>
    .a-bal {
        margin: 25% 0 0 0;
    }

    .a-bal sub {
        color: #963c45;
        font-size: 11px;
    }

    .red-msg {
        color: #963c45;
        font-size: 11px;
    }
    .btnalign {
        justify-content: space-between;
    }
    .btnalign > button {
        min-width: 48%;
        min-height: 45px;
    }
    .cardimgdiv img {
        min-height: 150px;
    }

    /******** tabs css ********/
    /*.tablinks .active {*/
    /*    background-color: #094042 !important;*/
    /*    color: #fff !important;*/
    /*    border-bottom: 1px solid #ffffff !important;*/
    /*}*/
    /*.tablinks li {*/
    /*    background-color: #eef1f2;*/
    /*    border: none;*/
    /*    width: 18%;*/
    /*    padding: 20px;*/
    /*    border-top-left-radius: 10px;*/
    /*    border-top-right-radius: 10px;*/
    /*    color: #606060;*/
    /*}*/
    /******** tabs css ********/

    .item>div {
        padding: 20px 20px;
        margin: 10px;
        background: var(--grey-02);
        color: #666;
        border-radius: 10px;
    }

    .post_image {
        text-align: right;
        width: 88px;
    }

    .post_image img {
        width: 24px;
        margin: 0px 5px;
    }

    .points-display {
        font-family: Poppins, sans-serif;
        font-size: 64px;
        font-weight: 600;
        color: #094042;
    }

    .purchase-points .price {
        background: #EEF2F2;
        font-family: Poppins;
        font-size: 18px;
        font-weight: 600;
        line-height: 27px;
        text-align: center;
        text-decoration-skip-ink: none;
        color: red;
        color: #094042;
        padding: 4px 15px;
        border-radius: 4px;
    }

    .rewards-buttons {
        width: 300px !important;
    }

    .withdraw-buttons {
        width: 100% !important;
    }

    ul#mainTab li {
        background-color: #f0f0f0;
    }
    ul#mainTab li a {
        color: #094042;
    }
    ul#mainTab li a.active {
        background-color: #094042;
        color: #f0f0f0;
    }


    .contentAreaSection {
        display: inline-block;
        width: 100%;
        margin: 5% 0;
    }

    .contentAreaSectionLeft {
        float: left;
        width: 49%;
    }

    .contentAreaSectionRight {
        width: 49%;
        float: right;
    }

    .contentAreaSectionLeft form button {
        float: left;
    }

    .contentAreaSectionRight h2 {
        float: right;
    }

    @media (max-width: 768px) {
        .rewards-buttons {
            width: 100% !important;
        }
    }
</style>


<div class="content" id="pageStarts">
    <div class="container">
        <div class="heading mb-4">
            <h2>My Rewards</h2>
        </div>
        <div class="divider mb-4">
            <hr>
        </div>

        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif

        <div class="MainPagetabsArea">
           
            <ul class="nav nav-tabs tablinks" id="mainTab" role="tablist">
                
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" 
                    id="withdrawrequesttab-tab" data-bs-toggle="tab" href="#withdrawrequesttab" 
                    role="tab" aria-controls="withdrawrequesttab" aria-selected="false">
                        Site Commission History
                    </a>
                </li>
            </ul>


            <?php  $sno = 1; ?>
            <div class="tab-content" id="mainTabContent">
                               <!-- Withdraw Request Tab-->
                <div class="tab-pane fade show active" id="withdrawrequesttab" role="tabpanel" aria-labelledby="withdrawrequesttab-tab">

                    <div class="table-responsive">
                        <table id="items_table" class="table items_table">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Total Coins</th>
                                    <th scope="col">Commission Coins</th>
                                    <th scope="col">Commission Amount</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action At</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($earn_points as $index => $point)
                                <tr>
                                    <td>{{  $earn_points->firstItem() + $index }} </td>
                                    <td>{{ ucfirst($point?->user?->name) }}</td>
                                    <td>{{ ucfirst($point->type) }}</td>
                                    <td>{{ $point->total_coins }}</td>
                                    <td>{{ $point->points }}</td>
                                    <td>{{ $point->amount }}</td>
                                    <td>{{ $point->description }}</td>
                                    <td>{{ $point->created_at }}</td>
                                </tr>
                                <?php  $sno += 1; ?>
                                @endforeach
                            </tbody>
                        </table>
                      @if (isset($earn_points) && $earn_points instanceof \Illuminate\Pagination\LengthAwarePaginator)
                          <div class="container my-4">
                              <div class="d-flex justify-content-center">
                                  {{ $earn_points->links() }}
                              </div>
                          </div>
                      @endif

                    </div>

                    <div id="ajaxLoader" style="display: none; position: fixed; top: 0; left: 0;
                        width: 100%; height: 100%; background: rgba(255,255,255,0.7);
                        z-index: 9999; justify-content: center; align-items: center;">
                        <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                            <img src="https://i.gifer.com/YCZH.gif" alt="Loading..." style="width: 220px;">
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- Main Page Tabs Ends-->

    </div>
</div>

<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>






