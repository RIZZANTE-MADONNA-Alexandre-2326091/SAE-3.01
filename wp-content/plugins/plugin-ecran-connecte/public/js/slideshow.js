let urlUpload = "/wp-content/uploads/media/";
let pdfUrl = null;
let numPage = 0; // Numéro de page courante
let totalPage = null; // Nombre de pages
let endPage = false;
let stop = false;

/**
 * Ce bloc de 4 lignes sert à lancer de manière asynchrone l'IFrame Player API d'après la documentation officielle de [Youtube Iframe API](https://developers.google.com/youtube/iframe_api_reference?hl=fr)
 * */
let tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
let firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

/**
 * Lien URL vers YouTube
 * */
let urlYoutube = "https://www.youtube.com/";

/**
 *
 * */
let player;

/**
 * Savoir si une vidéo YouTube est terminée
 * @defaultValue false
 * */
let done = false;

infoSlideShow();
scheduleSlideshow();

/**
 * Begin a slideshow if there is some informations
 */
function infoSlideShow()
{
    if(document.getElementsByClassName("myInfoSlides").length > 0) {
        console.log("-Début du diaporama");
        displayOrHide(document.getElementsByClassName("myInfoSlides"), 0);
    }
}

/**
 * Begin a slideshow if there is some informations
 */
function scheduleSlideshow()
{
    if(document.getElementsByClassName("mySlides").length > 0) {
        console.log("-Début du diaporama");
        displayOrHide(document.getElementsByClassName("mySlides"), 0);
    }
}

/**
 * Display a slideshow
 * @param slides Les slides (informations) qui défileront dans la partie "Information"
 * @param slideIndex index de la slide dans la liste {@linkcode slides}, c'est-à-dire le numéro de l'information
 */
function displayOrHide(slides, slideIndex)
{
    let timeout = 10000;
    if(slides.length > 0) {
        if(slides.length > 1) {
            for (let i = 0; i < slides.length; ++i) {
                slides[i].style.display = "none";
            }
        }

        if(slideIndex === slides.length) {
            console.log("-Fin du diaporama - On recommence");
            slideIndex = 0;
        }

        // Check if the slide exist
        if(slides[slideIndex] !== undefined) {

            console.log("--Slide n°"+ slideIndex);
            // Display the slide
            slides[slideIndex].style.display = "block";
            // Check child
            if(slides[slideIndex].childNodes) {
                var count = 0;
                // Try to find if it's a PDF or a video
                for(let i = 0; i < slides[slideIndex].childNodes.length; ++i) {
                    console.log("Affichage slide index " + i + ": " + (slides[slideIndex].childNodes[i].className));
                    // If is a PDF or a video
                    if (slides[slideIndex].childNodes[i].className === 'canvas_pdf') {

                        console.log("--Lecture de PDF");

                        count = count + 1;

                        // Generate the url
                        let pdfLink = slides[slideIndex].childNodes[i].id;
                        pdfUrl = urlUpload + pdfLink;

                        let loadingTask = pdfjsLib.getDocument(pdfUrl);
                        loadingTask.promise.then(function (pdf) {

                            totalPage = pdf.numPages;
                            ++numPage;

                            let div = document.getElementById(pdfLink);
                            let scale = 1.5;

                            if (stop === false) {
                                if (document.getElementById('the-canvas-' + pdfLink + '-page' + (numPage - 1)) != null) {
                                    console.log('----Supression page n°' + (numPage - 1));
                                    document.getElementById('the-canvas-' + pdfLink + '-page' + (numPage - 1)).remove();
                                }
                            }

                            if (totalPage >= numPage && stop === false) {
                                pdf.getPage(numPage).then(function (page) {

                                    console.log(slides.length);
                                    console.log(totalPage);
                                    if (slides.length === 1 && totalPage === 1 || totalPage === null && slides.length === 1) {
                                        stop = true;
                                    }

                                    console.log(stop);

                                    console.log("---Page du PDF n°" + numPage);

                                    let viewport = page.getViewport({scale: scale,});

                                    $('<canvas >', {
                                        id: 'the-canvas-' + pdfLink + '-page' + numPage,
                                    }).appendTo(div).fadeOut(0).fadeIn(2000);

                                    let canvas = document.getElementById('the-canvas-' + pdfLink + '-page' + numPage);
                                    let context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    let renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };

                                    // Give the CSS to the canvas
                                    if (slides === document.getElementsByClassName("mySlides")) {
                                        canvas.style.maxHeight = "99vh";
                                        canvas.style.maxWidth = "100%";
                                        canvas.style.height = "99vh";
                                        canvas.style.width = "auto";
                                    } else {
                                        canvas.style.maxHeight = "68vh";
                                        canvas.style.maxWidth = "100%";
                                        canvas.style.height = "auto";
                                        canvas.style.width = "auto";
                                    }

                                    page.render(renderContext);
                                });

                                if (numPage === totalPage) {
                                    // Reinitialise variables
                                    if (stop === false) {
                                        if (document.getElementById('the-canvas-' + pdfLink + '-page' + (totalPage)) != null) {
                                            document.getElementById('the-canvas-' + pdfLink + '-page' + (totalPage)).remove();
                                        }
                                    }
                                    console.log("--Fin du PDF");
                                    totalPage = null;
                                    numPage = 0;
                                    endPage = true;
                                    ++slideIndex;
                                    // Go to the next slide
                                }
                            }
                        });
                    }
                    /*
                    * Si c'est une vidéo,
                    * ⇒ différents traitements en fonction du type de la vidéo
                    * */
                    // If it's a YouTube short video
                    if (slides[slideIndex].childNodes[i].className === 'videosh') {
                        console.log("--Lecture vidéo Youtube short");
                        console.log(slides[slideIndex].childNodes[i]);

                        // TODO
                    }
                    // If it's a YouTube normal video
                    else if (slides[slideIndex].childNodes[i].className === 'videow') {
                        console.log("--Lecture vidéo Youtube classique");
                        console.log(slides[slideIndex].childNodes[i]);

                        // TODO
                    }
                    // If it's a local normal video
                    else if (slides[slideIndex].childNodes[i].className === 'localCvideo') {
                        console.log("--Lecture vidéo locale classique");
                        const listeVideosClassiqueLocal = document.querySelectorAll(".localCvideo");
                        for (let indexVideoClassiqueLocal = 0; indexVideoClassiqueLocal < listeVideosClassiqueLocal.length; ++indexVideoClassiqueLocal) {
                            let videoClassiqueLocal = listeVideosClassiqueLocal[indexVideoClassiqueLocal];
                            if (listeVideosClassiqueLocal[indexVideoClassiqueLocal] === slides[slideIndex].childNodes[i]) {
                                timeout = videoClassiqueLocal.duration * 1000;
                            }
                            console.log("timeout = " + timeout + "\tduration = " + videoClassiqueLocal.duration);
                            console.log(videoClassiqueLocal);
                            videoClassiqueLocal.load();
                            videoClassiqueLocal.currentTime = 0;
                            videoClassiqueLocal.play();
                            videoClassiqueLocal.onended = () => {
                                videoClassiqueLocal.pause();
                                videoClassiqueLocal.currentTime = 0;
                            }
                        }
                        // TODO
                    }
                    // If it's a local short video
                    else if (slides[slideIndex].childNodes[i].className === 'localSvideo') {
                        console.log("--Lecture vidéo locale short");
                        const listeVideosShortLocal = document.querySelectorAll(".localSvideo");
                        for (let indexVideoShortLocal = 0; indexVideoShortLocal < listeVideosShortLocal.length; ++indexVideoShortLocal) {
                            let videoShortLocal = listeVideosShortLocal[indexVideoShortLocal];
                            if (listeVideosShortLocal[indexVideoShortLocal] === slides[slideIndex].childNodes[i]) {
                                timeout = videoShortLocal.duration * 1000;
                            }
                            console.log("timeout = " + timeout + "\tduration = " + videoShortLocal.duration);
                            console.log(videoShortLocal);
                            videoShortLocal.load();
                            videoShortLocal.currentTime = 0;
                            videoShortLocal.play();
                            videoShortLocal.onended = () => {
                                videoShortLocal.pause();
                                videoShortLocal.currentTime = 0;
                            }
                        }
                        // TODO
                    }
                }
                if (count === 0) {
                    console.log("--Lecture image");
                    // Go to the next slide
                    ++slideIndex;
                }
            } else {
                // Go to the next slide
                ++slideIndex;
            }
            console.log("Slide " + slideIndex + " sur " + slides.length);
        }
    }

    if(slides.length !== 1 || totalPage !== 1) {
        setTimeout(function(){displayOrHide(slides, slideIndex)} , timeout);
    }
}


//TODO fonctions API Youtube et HTMLMediaElement
function onYouTubeIframeAPIReady() {
    console.log("API Youtube démarée");
    player = new YT.Player('videosh', {
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
        }
    });
}

function onPlayerReady(event) {
    const slides = document.getElementsByClassName("myInfoSlides");
    for (let i = 0; i < slides.length; ++i) {
        if (slides[i].childNodes && slides[i] !== undefined) {
            for (let j = 0; j < slides[i].childNodes.length; ++j) {
                event.target.setPlaybackQuality("default");
                if ((slides[i].childNodes[j].className === 'videosh' || slides[i].childNodes[j].className === 'videosh')
                    && slides[i].style.display === "block") {
                    console.log("Vidéo YT lancée");
                    event.target.seekTo(0);
                    event.target.playVideo();
                } else if ((slides[i].childNodes[j].className === 'videosh' || slides[i].childNodes[j].className === 'videosh')
                    && slides[i].style.display === "none") {
                    console.log("Vidéo YT arrêté");
                    stopVideo();
                }
            }
        }
    }
}

function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.PLAYING && !done) {
        setTimeout(stopVideo, event.target.getDuration());
        console.log("Vidéo YT terminée");
        done = true;
    }
}
function stopVideo() {
    player.stopVideo();
}
