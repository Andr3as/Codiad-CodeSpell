/*jshint worker:true*/
/*
 * Copyright (c) Andr3as
 * as-is and without warranty under the MIT License.
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
importScripts('typo/typo.js');

var typo;

self.addEventListener('message', function(e) {
    if (e.data.type == "setLanguage") {
        var lang = e.data.language;
        var settings = {
            dictionaryPath: "dictionaries"
        };
        typo = new Typo(lang, "", "", settings);
    } else if (e.data.type == "getSuggestions") {
        var mistakes = e.data.mistakes;
        //Get suggestions
        var part = {}, result = [];
        for (var i = 0; i < mistakes.length; i++) {
            part = {};
            part.word = mistakes[i].word;
            part.suggestions = typo.suggest(mistakes[i].word);
            result.push(part);
        }
        
        var data    = JSON.stringify(result);
        //Post result
        postMessage({data: data});
    }
}, false);