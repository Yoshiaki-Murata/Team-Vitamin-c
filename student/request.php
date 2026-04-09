<?php
require_once __DIR__ . "/../inc/function.php";
?>

<?php include __DIR__ . "/../inc/header.php" ?>
<main class="container mt-5">
    <h1 class="mb-5 text-center">予約画面</h1>
    <div class="text-center">
        <select name="date" id="date" class="mb-3 d-inline-block form-select w-auto">
            <option value="2026-05-09">2026/5/9</option>
            <option value="2026-05-16">2026/5/16</option>
            <option value="2026-05-23">2026/5/23</option>
        </select>

        <ul class="row mx-auto list-unstyled justify-content-center">
            <li class="col card m-1">
                <p class="card-title text-center">10:00</p>
                <p class="card-text text-center">空<span>3</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">11:00</p>
                <p class="card-text text-center">空<span>2</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">12:00</p>
                <p class="card-text text-center">空<span>3</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">13:00</p>
                <p class="card-text text-center">空<span>1</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">14:00</p>
                <p class="card-text text-center">満<span>×</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">15:00</p>
                <p class="card-text text-center">満<span>×</span></p>
            </li>
            <li class="col card m-1">
                <p class="card-title text-center">16:00</p>
                <p class="card-text text-center">満<span>×</span></p>
            </li>
        </ul>
    </div>
</main>