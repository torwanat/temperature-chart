<?php
require __DIR__ . "/data.php";

$scale = 7;
$vertical_interval = ($height - 2 * $padding) / $scale;
$horizontal_interval = ($width - 2 * $padding) / count($data);

foreach ($data as $position => $value) {
    $whole = floor($value);
    $decimal = $value - $whole;
    $x = $padding + ($horizontal_interval * ($position + 1));
    $y = 0;
    if ($value == null || $value > 37 || $value < 36) {
        $y = $height - $padding;
    } else {
        $y = $height - $padding - ($vertical_interval * ($decimal * 10 + 2) / 2);
    }
    array_push($coords, [floor($x), floor($y)]);
}
?>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="styles/index.css">
    <title>Main</title>
</head>

<body>
    <map name='chartmap' id="chartmap">
    </map>
    <img src='chart.php' alt='Chart' usemap='#chartmap' />

    <dialog id="dlEditValue">
        <div class="form-container">
            <input type="hidden" value="" id="inNewValue">
            <input type="hidden" value="" id="inPosition">
            <input type="text" name="inTemperature" id="inTemperature">
            <label for="inTemperature">Temperatura:</label>
            <button onclick="setValue('ill')">Choroba</button>
            <button onclick="setValue('none')">Brak pomiaru</button>
            <button onclick="setValue('save')">Zapisz</button>
            <button onclick="setValue('cancel')">Anuluj</button>
        </div>
    </dialog>
</body>

<script>
    const data = <?php echo json_encode($data); ?>;
    const padding = <?php echo $padding; ?>;
    const width = <?php echo $width; ?>;
    const height = <?php echo $height; ?>;
    const scale = 7;
    const verticalInterval = (height - 2 * padding) / scale;
    const horizontalInterval = (width - 2 * padding) / data.length;
    const coordsArr = [];
    const mpChartmap = document.getElementById("chartmap");
    const dialog = document.getElementById("dlEditValue");
    const newValueInput = document.getElementById("inNewValue");
    const temperatureInput = document.getElementById("inTemperature");
    const positionInput = document.getElementById("inPosition");

    data.forEach((value, position) => {
        const whole = Math.floor(value);
        const decimal = value - whole;
        const x = padding + (horizontalInterval * (position + 1));
        let y = 0;

        if (value == null || value > 37 || value < 36) {
            y = height - padding;
        } else {
            y = height - padding - (verticalInterval * (decimal * 10 + 2) / 2);
        }

        coordsArr.push([Math.floor(x), Math.floor(y)]);
    });

    coordsArr.forEach((coords, position) => {
        const area = document.createElement("area");
        area.shape = "circle";
        area.coords = coords[0] + "," + coords[1] + ",10";
        area.href = "#";
        area.addEventListener("click", () => { updateValue(position + 1) });
        mpChartmap.appendChild(area);
    });

    const updateValue = (position) => {
        temperatureInput.value = "";
        newValueInput.value = "";
        positionInput.value = position;
        dialog.showModal();
    }

    //TODO: brak pomiaru nie działa
    const setValue = (value) => {
        switch (value) {
            case "ill":
                newValueInput.value = 39;
                break;
            case "none":
                newValueInput.value = null;
                break;
            case "save":
                newValueInput.value = temperatureInput.value;
                break;
            default:
                break;
        }
        dialog.close();

        const dataToSend = {
            id: positionInput.value,
            value: newValueInput.value
        }

        const jsonToSend = JSON.stringify(dataToSend);
        fetch("./send.php", { method: "POST", body: jsonToSend }).then((response) => {
            return response.text();
        }).then((response) => {
            console.log(response);
        });
    }

</script>