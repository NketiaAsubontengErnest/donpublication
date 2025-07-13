<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome <span class="text-primary"></span><?= Auth::getFirstname() ?></span></h3>
                    <?php if (Auth::comparepass()) : ?>
                        <h6 class="font-weight-normal mb-0">Your username is the same as your password! You have <span class="text-primary"><a href="<?= HOME ?>/profile">to change the password!</a></span></h6>
                    <?php endif ?>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <b>Season:</b> <?= isset($_SESSION['seasondata']->SeasonName) ? $_SESSION['seasondata']->SeasonName : '<code>No Season set</code>' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (Auth::access('stores')) : ?>
        <div class="row">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <a href="<?= HOME ?>/books" class="text-white" style="text-decoration: none;">
                            <p class="mb-4">Total Books in stock</p>
                            <?php try { ?>
                                <p class="fs-30 mb-5"><?= esc(number_format($rows['books']->quantity)) ?></p>
                                <p><?= esc(number_format((float)((($rows['books']->quantity) / ($rows['order']->quantsupp + $rows['books']->quantity)) * 100), 2, '.', '')) ?>
                                    %</p>
                            <?php } catch (\Throwable $th) {
                            } ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <a href="<?= HOME ?>/subjects/summary" class="text-white" style="text-decoration: none;">
                            <p class="mb-4">Total Books Supplied</p>
                            <?php try { ?>
                                <p class="fs-30 mb-5"><?= esc(number_format($rows['order']->quantsupp - $rows['order']->retverquant)) ?></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="">Sample: <?= esc(number_format($rows['sampleorder']->quantsuppSamp)) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?= esc(number_format((float)((($rows['order']->quantsupp - $rows['order']->retverquant) / ($rows['books']->quantity + ($rows['order']->quantsupp - $rows['order']->retverquant))) * 100), 2, '.', '')) ?>
                                            %</p>
                                    </div>
                                </div>
                            <?php } catch (\Throwable $th) {
                            } ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <a href="<?= HOME ?>/subjects/summary" class="text-white" style="text-decoration: none;">
                            <p class="mb-4">Total Books Returned</p>
                            <p class="fs-30 mb-5"><?= esc(number_format($rows['order']->retverquant)) ?></p>
                            <p>
                                <?php
                                try {
                                    echo esc(number_format((float)((($rows['order']->retverquant) / (($rows['order']->quantsupp))) * 100), 2, '.', '')) . '%';
                                } catch (\Throwable $th) {
                                }
                                ?>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body text-white">
                        <a href="<?= HOME ?>/books/reorderbooks" class="text-white" style="text-decoration: none;">
                            <p class="mb-4">Reorder Level</p>
                            <?php try { ?>
                                <p class="fs-30 mb-5"><?= esc(number_format($rows['outstock']->les)) ?> Books</p>
                                <p><?= esc(number_format((float)((($rows['outstock']->les) / (($rows['ttbooks']->numbers))) * 100), 2, '.', '')) ?> %</p>
                            <?php } catch (\Throwable $th) {
                            } ?>
                        </a>
                    </div>
                </div>
            </div>

        <?php endif ?>

        <?php if (Auth::getRank() == 'marketer') : ?>
            <div class="row">
                <div class="col-md-3 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                        <div class="card-body">
                            <p class="mb-4">Total Orders</p>
                            <?php try { ?>
                                <p class="fs-30 mb-5"><?= esc(number_format($rows['orders']->orderss)) ?></p>
                                <div class="row">
                                    <div class="col-6">
                                        <p><?= esc($rows['ordersnotver']->ordersnv) ?> Verified</p>
                                    </div>
                                    <div class="col-6">
                                        <p><?= esc($rows['orders']->orderss - $rows['ordersnotver']->ordersnv) ?> Unverified</p>
                                    </div>
                                </div>
                            <?php } catch (\Throwable $th) {
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                        <div class="card-body">
                            <p class="mb-4">Total Books Supplied</p>
                            <?php try { ?>
                                <p class="fs-30 mb-2"><?= esc(number_format($rows['order']->quantsupp - $rows['order']->retverquant)) ?></p>

                                <div class="row mb-2">
                                    <div class="col-4">
                                        <small>Cash: <?= esc(number_format($rows['ordercash']->quantsuppcash ?? 0)) ?></small>
                                    </div>
                                    <div class="col-4">
                                        <small>Credit: <?= esc(number_format($rows['ordercredit']->quantsuppcredit ?? 0)) ?></small>
                                    </div>
                                    <div class="col-4">
                                        <small>Others: <?= esc(number_format($rows['orderothers']->quantsuppothers ?? 0)) ?></small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <p>Samples: <?= esc(number_format($rows['ordersamp']->quantsuppsamp)) ?></p>
                                    </div>
                                    <div class="col-6">
                                        <p>Shared: <?= esc(number_format($rows['ttshered']->ttshered)) ?></p>
                                    </div>

                                </div>
                            <?php } catch (\Throwable $th) {
                                //throw $th;
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 stretch-card transparent">
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="mb-4">Total Books Returned</p>
                            <p class="fs-30 mb-5"><?= esc(number_format($rows['order']->retverquant)) ?></p>
                            <p>
                                <?php
                                try {
                                    //code... 
                                    echo esc(number_format((float)((($rows['order']->retverquant) / (($rows['order']->quantsupp))) * 100), 2, '.', '')) . '%';
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4 stretch-card transparent">
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="mb-4">My Customers</p>
                            <?php try { ?>
                                <p class="fs-30 mb-5"><?= esc(number_format($rows['ttcusts']->ttcust)) ?></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="fs-25 "><?= esc(number_format($rows['ttcustsv'])) ?> Visited</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p></p>
                                    </div>

                                </div>

                            <?php } catch (\Throwable $th) {
                                //throw $th;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h5 class="font-weight-bold">Choose A Season</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <form id="seasonForm" action="" method="post">
            <div class="">
                <div class="row">
                    <?php
                    $countses = 0;
                    foreach ($season as $seas): ?>
                        <?php if ($seas->id != $_SESSION['seasondata']->id):
                            $countses++;
                        ?>

                            <div class="col-md-3 mb-2 stretch-card transparent">
                                <button type="button" class="card card-light-blue w-100"
                                    onclick="confirmSubmission('<?= esc($seas->id) ?>', '<?= esc($seas->SeasonName) ?>')">
                                    <div class="card-body" style="text-align: left;">
                                        <div class="row">
                                            <p class="fs-30 mb-2"><?= esc($seas->SeasonName) ?></p>

                                            <?php
                                            $recovery = 0;
                                            $returns = 0;
                                            $netamt = 0;

                                            try {
                                                $netamt = $seas->OfficTotalDept->totaldept - $seas->OfficTotalDept->totaldisc;
                                            } catch (\Throwable $th) {
                                                // Handle exception or leave blank
                                            }

                                            try {
                                                $returns = ($seas->OfficTotalDept->total_net_returns /
                                                    ($seas->OfficTotalDept->total_net_returns + $netamt)) * 100;
                                            } catch (\Throwable $th) {
                                                // Handle exception or leave blank
                                            }
                                            ?>

                                            <div class="col-md-6">
                                                <p class="mb-2"><b>Gross Sales:</b> <?= esc(number_format($seas->OfficTotalDept->totaldept, 2)) ?></p>
                                                <p class="mb-2"><b>Net Sales:</b> <?= esc(number_format($netamt, 2)) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2"><b>Balance:</b> <?= esc(number_format($netamt - $seas->OfficTotal->totalpayed, 2)) ?></p>
                                                <p class="mb-2"><b>Status:</b> <?= esc($seas->SeasonStatus) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>

                        <?php endif ?>
                    <?php endforeach; ?>
                    <?php if ($countses == 0): ?>
                        <p>there is no order season</p>
                    <?php endif ?>
                </div> <!-- End row -->
            </div>
            <input type="hidden" id="selectedSeason" name="season" value=""> <!-- Hidden input to hold the selected season -->
        </form>

        <script src="<?= ASSETS ?>/js/flush_arlert1.js"></script>
        <script>
            // Function to show SweetAlert confirmation
            function confirmSubmission(seasonId, seasonName) {
                Swal.fire({
                    title: 'Switch Season',
                    text: "Do you want to switch the season to '" + seasonName + "'?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user clicks "Yes", submit the form
                        document.getElementById('selectedSeason').value = seasonId;
                        document.getElementById('seasonForm').submit();
                    }
                });
            }
        </script>

        <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>