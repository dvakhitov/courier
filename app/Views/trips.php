<?php

include __DIR__ . '/header.php'; ?>

<h2>Добавление поездки</h2>
<form id="tripForm">
    <label>Регион:
        <select name="region_id">
            <?php
            /** @var \App\Model\Region $region */
            foreach ($regions as $region) {
                echo '<option value="' . $region->getId() . '">' . $region->getName() . '</option>';
            }
            ?>
        </select>
    </label>
    <br>
    <label>Дата выезда:
        <input type="date" name="departure_date" required>
    </label>
    <br>
    <label>Курьер:
        <select name="courier_id">
            <?php

            /** @var \App\Model\Courier $courier */
            foreach ($couriers as $courier) {
                echo '<option value="' . $courier->getId() . '">' . $courier->getFullName() . '</option>';
            }
            ?>
        </select>
    </label>
    <br>
    <button type="submit">Добавить поездку</button>
</form>

<div id="result"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#tripForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '?action=add',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success'){
                    $('#result').html('<p>Поездка успешно добавлена!</p>');
                } else {
                    $('#result').html('<p>Ошибка: ' + response.message + '</p>');
                }
            },
            error: function(e) {

                $('#result').html('<p>'+e.responseText+'</p>');
            }
        });
    });
</script>

<?php include __DIR__ . '/footer.php'; ?>
