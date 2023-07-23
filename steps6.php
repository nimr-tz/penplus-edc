<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/css/bs-stepper.css">
    <title>bs-stepper virtual test</title>
    <style>
        /*!
 * bsStepper v{version} (https://github.com/Johann-S/bs-stepper)
 * Copyright 2018 - {year} Johann-S <johann.servoire@gmail.com>
 * Licensed under MIT (https://github.com/Johann-S/bs-stepper/blob/master/LICENSE)
 */

        .bs-stepper .step-trigger {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.5;
            color: #6c757d;
            text-align: center;
            text-decoration: none;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            background-color: transparent;
            border: none;
            border-radius: .25rem;
            transition: background-color .15s ease-out, color .15s ease-out;
        }

        .bs-stepper .step-trigger:not(:disabled):not(.disabled) {
            cursor: pointer;
        }

        .bs-stepper .step-trigger:disabled,
        .bs-stepper .step-trigger.disabled {
            pointer-events: none;
            opacity: .65;
        }

        .bs-stepper .step-trigger:focus {
            color: #007bff;
            outline: none;
        }

        .bs-stepper .step-trigger:hover {
            text-decoration: none;
            background-color: rgba(0, 0, 0, .06);
        }

        @media (max-width: 520px) {
            .bs-stepper .step-trigger {
                flex-direction: column;
                padding: 10px;
            }
        }

        .bs-stepper-label {
            display: inline-block;
            margin: .25rem;
        }

        .bs-stepper-header {
            display: flex;
            align-items: center;
        }

        @media (max-width: 520px) {
            .bs-stepper-header {
                margin: 0 -10px;
                text-align: center;
            }
        }

        .bs-stepper-line,
        .bs-stepper .line {
            flex: 1 0 32px;
            min-width: 1px;
            min-height: 1px;
            margin: auto;
            background-color: rgba(0, 0, 0, .12);
        }

        @media (max-width: 400px) {

            .bs-stepper-line,
            .bs-stepper .line {
                flex-basis: 20px;
            }
        }

        .bs-stepper-circle {
            display: inline-flex;
            align-content: center;
            justify-content: center;
            width: 2em;
            height: 2em;
            padding: .5em 0;
            margin: .25rem;
            line-height: 1em;
            color: #fff;
            background-color: #6c757d;
            border-radius: 1em;
        }

        .active .bs-stepper-circle {
            background-color: #007bff;
        }

        .bs-stepper-content {
            padding: 0 20px 20px;
        }

        @media (max-width: 520px) {
            .bs-stepper-content {
                padding: 0;
            }
        }

        .bs-stepper.vertical {
            display: flex;
        }

        .bs-stepper.vertical .bs-stepper-header {
            flex-direction: column;
            align-items: stretch;
            margin: 0;
        }

        .bs-stepper.vertical .bs-stepper-pane,
        .bs-stepper.vertical .content {
            display: block;
        }

        .bs-stepper.vertical .bs-stepper-pane:not(.fade),
        .bs-stepper.vertical .content:not(.fade) {
            display: block;
            visibility: hidden;
        }

        .bs-stepper-pane:not(.fade),
        .bs-stepper .content:not(.fade) {
            display: none;
        }

        .bs-stepper .content.fade,
        .bs-stepper-pane.fade {
            visibility: hidden;
            transition-duration: .3s;
            transition-property: opacity;
        }

        .bs-stepper-pane.fade.active,
        .bs-stepper .content.fade.active {
            visibility: visible;
            opacity: 1;
        }

        .bs-stepper-pane.active:not(.fade),
        .bs-stepper .content.active:not(.fade) {
            display: block;
            visibility: visible;
        }

        .bs-stepper-pane.dstepper-block,
        .bs-stepper .content.dstepper-block {
            display: block;
        }

        .bs-stepper:not(.vertical) .bs-stepper-pane.dstepper-none,
        .bs-stepper:not(.vertical) .content.dstepper-none {
            display: none;
        }

        .vertical .bs-stepper-pane.fade.dstepper-none,
        .vertical .content.fade.dstepper-none {
            visibility: hidden;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>bs-stepper</h1>
        <div class="row">
            <div class="col-md-12 mt-5">
                <h2>Linear stepper</h2>
                <div id="stepper1" class="bs-stepper">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#test-l-1">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">First step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-l-2">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Second step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-l-3">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Third step</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="test-l-1" class="content">
                            <p class="text-center">test 1</p>
                            <button class="btn btn-primary" onclick="stepper1.next()">Next</button>
                        </div>
                        <div id="test-l-2" class="content">
                            <p class="text-center">test 2</p>
                            <button class="btn btn-primary" onclick="stepper1.next()">Next</button>
                        </div>
                        <div id="test-l-3" class="content">
                            <p class="text-center">test 3</p>
                            <button class="btn btn-primary" onclick="stepper1.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper1.previous()">Previous</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <h2>Non linear stepper</h2>
                <div id="stepper2" class="bs-stepper">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#test-nl-1">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">First step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-nl-2">
                            <div class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Second step</span>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-nl-3">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Third step</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="test-nl-1" class="content">
                            <p class="text-center">test 3</p>
                            <button class="btn btn-primary" onclick="stepper2.next()">Next</button>
                        </div>
                        <div id="test-nl-2" class="content">
                            <p class="text-center">test 4</p>
                            <button class="btn btn-primary" onclick="stepper2.next()">Next</button>
                        </div>
                        <div id="test-nl-3" class="content">
                            <p class="text-center">test 5</p>
                            <button class="btn btn-primary" onclick="stepper2.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper2.previous()">Previous</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <h2>Vertical stepper</h2>
                <div id="stepper3" class="bs-stepper vertical">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#test-lv-1">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">First step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-lv-2">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Second step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-lv-3">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Third step</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="test-lv-1" class="content">
                            <p class="text-center">test 3</p>
                            <button class="btn btn-primary" onclick="stepper3.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper3.previous()">Previous</button>
                        </div>
                        <div id="test-lv-2" class="content">
                            <p class="text-center">test 4</p>
                            <button class="btn btn-primary" onclick="stepper3.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper3.previous()">Previous</button>
                        </div>
                        <div id="test-lv-3" class="content">
                            <p class="text-center">test 5</p>
                            <button class="btn btn-primary" onclick="stepper3.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper3.previous()">Previous</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <h2>Linear vertical stepper without fade</h2>
                <div id="stepper4" class="bs-stepper vertical">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#test-vlnf-1">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">First step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-vlnf-2">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Second step</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-vlnf-3">
                            <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Third step</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="test-vlnf-1" class="content">
                            <p class="text-center">test 1</p>
                            <button class="btn btn-primary" onclick="stepper4.next()">Next</button>
                        </div>
                        <div id="test-vlnf-2" class="content">
                            <p class="text-center">test 2</p>
                            <button class="btn btn-primary" onclick="stepper4.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper4.previous()">Previous</button>
                        </div>
                        <div id="test-vlnf-3" class="content">
                            <p class="text-center">test 3</p>
                            <button class="btn btn-primary" onclick="stepper4.next()">Next</button>
                            <button class="btn btn-primary" onclick="stepper4.previous()">Previous</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="dist/js/bs-stepper.js"></script>
    <script>
        var stepper1Node = document.querySelector('#stepper1')
        var stepper1 = new Stepper(document.querySelector('#stepper1'))

        stepper1Node.addEventListener('show.bs-stepper', function(event) {
            console.warn('show.bs-stepper', event)
        })
        stepper1Node.addEventListener('shown.bs-stepper', function(event) {
            console.warn('shown.bs-stepper', event)
        })

        var stepper2 = new Stepper(document.querySelector('#stepper2'), {
            linear: false,
            animation: true
        })
        var stepper3 = new Stepper(document.querySelector('#stepper3'), {
            animation: true
        })
        var stepper4 = new Stepper(document.querySelector('#stepper4'))
    </script>
</body>

</html>