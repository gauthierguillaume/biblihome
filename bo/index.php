<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

if(isset($_SESSION['auth'])){

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

    $domaine = "Dashboard";
    $sousDomaine = "Home / Dashboard";

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/analytics.php');

    var_dump($_SESSION['auth']);

    ?>

        <div class="records table-responsive">

            <div class="record-header">
                <div class="add">
                    <span>Entries</span>
                    <select name="" id="">
                        <option value="">ID</option>
                    </select>
                    <button>Add record</button>
                </div>

                <div class="browse">
                    <input type="search" placeholder="Search" class="record-search">
                    <select name="" id="">
                        <option value="">Status</option>
                    </select>
                </div>
            </div>

            <div>
                <table width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><span class="las la-sort"></span> CLIENT</th>
                            <th><span class="las la-sort"></span> TOTAL</th>
                            <th><span class="las la-sort"></span> ISSUED DATE</th>
                            <th><span class="las la-sort"></span> BALANCE</th>
                            <th><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Andrew Bruno</h4>
                                        <small>bruno@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                -$205
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                -$205
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $2171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                <span class="paid">Paid</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Andrew Bruno</h4>
                                        <small>bruno@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                -$205
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                <span class="paid">Paid</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $2171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                <span class="paid">Paid</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Andrew Bruno</h4>
                                        <small>bruno@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                -$205
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $3171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                -$205
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5033</td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(img/1.jpeg)"></div>
                                    <div class="client-info">
                                        <h4>Exty Bruno</h4>
                                        <small>exty@crossover.org</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                $2171
                            </td>
                            <td>
                                19 April, 2022
                            </td>
                            <td>
                                <span class="paid">Paid</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>

        </div>
                
    <?php

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');

}else{
    echo "<script language='javascript'>
            document.location.replace('_views/login.php')
            </script>";
}

?>