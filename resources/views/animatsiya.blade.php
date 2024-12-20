<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AX3021</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .left-panel {
            flex: 2;
            max-width: 300px;
            padding-top: 130px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(8, 50px);
            gap: 5px;
            margin-top: 20px;
        }

        .cell {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 14px;
            font-weight: bold;
        }

        .highlight {
            background-color: #ffeb3b;
        }

        #log {
            background: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            height: 300px;
            overflow-y: auto;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <button onclick="startAnimation()">Start Animation</button>
            <div id="log">Logs:</div>
        </div>
        
        <div class="right-panel">
            <h1>AX3021</h1>
            <p>Kalit taqsimlash pratakoli</p>
            <button onclick="window.location.href='/'">Go to Home</button>
            <div class="grid" id="grid">
                <!-- 64 cells will be dynamically generated -->
            </div>
        </div>
    </div>

    <script>
        // Statik 2048-bitli sonni kiritish
        const fiatNumber = BigInt("61922996200849027092373840794974198366624809097381747439384692114606755367386418141654154719133210408129273945135705224597275322269519687042477830912559249296799342619558780575507938236936116982576154945830129786286821271041825867993077159245391138589593248921478467351092276366108705282111083994883030281811429000985067679415882063513442457011943839720826582652289108048414053180169721587138545904121600428682482809145088804112597330583984498627587236356712692653135735260677995939976162822305218289779163342397669766455119347685681281793695160236606449797724858102829227517896828451383123053815492763180700009135746315709769787524061916262926165017966225552485499157196381125817897132920975191905184725707816635298220836910977105453301996500350001376384870046714245922538020591252195411509872975018053136044261031368478869978585955995924698628344106249293213192237713061344580447760165929782211684512673708362697780823000555911377208341355463458779047562658651420410209517239751295347272582371643876019859731406754876941578382322581959215169886022139891329585329970005134167799306236096856398342823609883117969076540635960831629768043994174893656664400366197869043797756160130700800662239529405810961593526262568450558161844897158");

        // Initialize the grid
        const grid = document.getElementById("grid");
        for (let i = 0; i < 64; i++) {
            const cell = document.createElement("div");
            cell.className = "cell";
            cell.id = `cell-${i}`;
            cell.textContent = i;
            grid.appendChild(cell);
        }

        // Feige-Fiat-Shamir animation logic
        function startAnimation() {
            const log = document.getElementById("log");
            log.innerHTML = ""; // Clear previous logs

            let number = fiatNumber; // Statik 2048-bit son
            logMessage(`Starting 2048-bit number: ${number}`);

            const interval = setInterval(() => {
                if (number < 1n) {
                    clearInterval(interval);
                    logMessage("Animation complete.");
                    return;
                }

                const index = Number(number % 64n);
                const cell = document.getElementById(`cell-${index}`);
                cell.classList.add("highlight");

                const value = (number / 10n) % 16n;
                const hexValue = value.toString(16).toUpperCase();
                cell.textContent = hexValue;
                logMessage(`Index: ${number} % 64 = ${index}, Stored value: (${number} / 10) % 16 = ${hexValue}`);

                number = number / 10n;
            }, 500); // 500ms delay between steps
        }

        function logMessage(message) {
            const log = document.getElementById("log");
            const p = document.createElement("p");
            p.textContent = message;
            log.appendChild(p);
            log.scrollTop = log.scrollHeight; // Auto-scroll to the bottom
        }
    </script>
</body>
</html>
