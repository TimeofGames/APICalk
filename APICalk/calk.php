<!DOCTYPE HTML>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/6.6.4/math.js"></script>
    <style>
        .btn {
            width: 58px;
            height: 40px;
            display: inline-block;
            text-align: center;
            line-height: 40px;
            user-select: none;
            border: 1px solid #d5d5d5;
        }

        .btn.num {
            background: #e5e5e5;
        }

        .btn.sign {
            background: #c5c5c5;
        }

        .btn:hover {
            background: #EEE;
            color: #000;
        }

        #calc-wrap {
            padding: 10px;
            width: 100%;
            text-align: center;
            background: #FFF;

        }

        #calc {
            width: 240px;
            display: inline-block;
        }

        output {
            display: flex;
            justify-content: right;
            white-space: pre-line;
            width: 240px;
            height: 80px;
            background: #e5e5e5;
            font-size: 1.4em;
            font-weight: bold;
            box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.3),
            inset -1px -1px 1px rgba(0, 0, 0, 0.3);
        }
    </style>
    <title>Калькулятор</title>
</head>
<body>
<?php
session_start();
if($_SESSION['auth'] == false || empty($_SESSION['auth'])): ?>
    <?
    unset($_SESSION['auth']);
    header('Location: /'); ?>
<?php else: ?>
<div id="calc-wrap">
    <div id="calc">
        <output>0</output>
    </div>
</div>


<script>
    // код начнёт выполняться после загрузки страницы
    // когда окно загрузится, сработает метод window.onLoad
    window.addEventListener('load', function OnWindowLoaded() {
        // набор кнопок
        let signs = [
            'CE', 'C', 'D', '/',
            '1', '2', '3', '*',
            '4', '5', '6', '-',
            '7', '8', '9', '+',
            '±', '0', '.', '='
        ];

        let calc = document.getElementById('calc');

        const output = document.querySelector('output')

        signs.forEach(function (sign) {
            let signElement = document.createElement('div');
            signElement.innerHTML = sign;
            let number = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            if (sign in number) {
                signElement.className = 'btn num';
            } else {
                signElement.className = 'btn sign';
            }
            calc.appendChild(signElement);
        });

        document.querySelectorAll('#calc-wrap .btn').forEach(function (button) {
            button.addEventListener('click', onButtonClick);
        });

        document.addEventListener('keydown', event => {
            if ((event.key).match(/[0-9%\/*\-+.\(\)=]|Backspace|Enter/)) ButtonClick(event.key)
        })

        function onButtonClick(e) {
            ButtonClick(e.target.innerHTML);
        }

        function ButtonClick(button) {
            if (button === 'C') {
                output.textContent = '0';
            } else if (button.match(/=|Enter/)) {
                try {
                    let preparString = '';
                    output.textContent.split('').forEach(function (sign) {
                        if (sign.match(/[0-9%\/*\-+.\(\)=]/)) preparString += sign;
                    })
                    output.textContent = math.evaluate(preparString)
                } catch {
                    let oldValue = output.textContent
                    let newValue = 'недопустимое выражение'
                    output.textContent = newValue
                    setTimeout(() => {
                        output.textContent = oldValue
                    }, 1500)
                }
            } else if (button === '±') {
                let newLine = '';
                let oldLine = output.textContent.split('');
                if (output.textContent.includes('\r\n')) {
                    for (let i = 0; i < output.textContent.length; i++) {
                        if (output.textContent[i] === '\n' && output.textContent[i + 1] === '-') {
                            newLine += '\n';
                            i++;
                        } else if (output.textContent[i] === '\n') {
                            newLine += '\n-'
                        } else {
                            newLine += output.textContent[i]
                        }
                    }
                } else {
                    oldLine.forEach(function (sign, index) {
                        if (index === 0 && sign != '-') {
                            newLine += ('-' + sign)
                        } else if (index === 0 && sign == '-') {
                            newLine += ''
                        } else {
                            newLine += sign
                        }
                    })
                }
                output.textContent = newLine;
            } else if (button === 'D') {
                if (output.textContent[output.textContent.length - 1] === '\n') {
                    output.textContent = output.textContent.substring(0, output.textContent.length - 3);
                } else {
                    output.textContent = output.textContent.substring(0, output.textContent.length - 1);
                }
            } else if (button.match(/CE|Backspace/)) {
                let newLine = '';
                let oldLine = output.textContent.split('');
                if (output.textContent.includes('\r\n')) {
                    let i = 0;
                    while (oldLine[i] != '\n') {
                        newLine += oldLine[i];
                        i++;
                    }
                    newLine += '\n'
                } else {
                    newLine = '';
                }
                output.textContent = newLine;
            } else if (output.textContent === '0') {
                if (button === '.') {
                    output.textContent = ('0' + button);
                } else if (button === '±') {
                    output.textContent = ('-');
                } else {
                    output.textContent = button;
                }
            } else {
                let numbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.'];
                if (!(numbers.includes(button))) {
                    output.textContent += (button + "\r\n");
                } else {
                    output.textContent += button;
                }
            }
            if (output.textContent === '') {
                output.textContent = '0'
            }
        }
    });
</script>
<?php endif; ?>
</body>

</html>