/* jshint browser: true */
/* global Typo */
/*
 * Copyright (c) Codiad & Andr3as, distributed
 * as-is and without warranty under the MIT License.
 * See http://opensource.org/licenses/MIT for more information. 
 * This information must remain intact.
 */

(function(global, $){
    
    var codiad = global.codiad,
        scripts = document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    $(function() {
        codiad.CodeSpell.init();
    });

    codiad.CodeSpell = {
        
        path: curpath,
        mistakes: [],
        typo: null,
        fn: function(){},
        
        init: function() {
            var _this = this;
            this.fn = function() {
                var lang = _this.getLanguage();
                _this.setDictionary(lang);
                //Load user dictionary
                _this.loadOwnDictionary();
            };
            $.getScript(this.path + "typo/typo.js", this.fn);
            
            $('.sb-right-content a:nth(1)').after('<a onclick="codiad.CodeSpell.check(true); return false;"><span class="icon-doc-text bigger-icon"></span>Spellcheck</a>');
            
            amplify.subscribe('settings.loaded', this.fn);
            amplify.subscribe('settings.changed', this.fn);
            amplify.subscribe('active.onSave', function(){
                var display = _this.getDisplayOnSave();
                _this.check(display);
            });
        },
        
        /**
         * @name selectLines
         * @argument (object) mistake
         */
        addMistake: function(mistake) {
            var _this = this;
            $.getJSON(this.path + "controller.php?action=addMistakeToOwnDictionary&mistake=" + mistake.word, function(result){
                if (result.status == "success") {
                    codiad.message.success(i18n("Mistake added!"));
                    _this.loadOwnDictionary();
                } else {
                    codiad.message.error(i18n("Failed to add mistake to own dictionary!"));
                }
            });
        },
        
        check: function(display) {
            var editor = codiad.editor.getActive();
            if (editor === null || this.typo === null) {
                return false;
            }
            
            var complete_check = ["markdown", "text"];
            var mode = this.__getSession().getMode().$id.replace("ace/mode/", "");
            var content = "", words = "";
            
            if (complete_check.indexOf(mode) == -1) {
                //Get comments
                content = this.getComments();
            } else {
                //Get content
                content = codiad.editor.getContent();
            }
            words = this.getUniqueWords(content);
            
            var is_correct = false;
            var lines = [], wrong_words = [];
            for (var i = 0; i < words.length; i++) {
                is_correct = this.typo.check(words[i]);
                if (!is_correct) {
                    lines = this.getLinesForWord(words[i]);
                    wrong_words.push({
                        "word": words[i],
                        "lines": lines
                    });
                    
                    console.log(lines, words[i]);
                }
            }
            
            if (wrong_words.length > 0) {
                codiad.message.error(i18n("Document contains mistakes"));
                this.mistakes = wrong_words;
                if (display) {
                    //Display dialog
                    codiad.modal.load(400, this.path + "dialog.php?action=view");
                }
            } else {
                codiad.message.success(i18n("No mistakes found"));
            }
        },
        
        deleteUserDictionary: function() {
            var _this = this;
            $.getJSON(this.path + "controller.php?action=deleteUserDictionary", function(result){
                codiad.message[result.status](i18n(result.message));
                if (result.status == "success") {
                    _this.fn();
                }
            });
        },
        
        getComments: function(content) {
            var session = this.__getSession();
            var lineCommentStart = session.getMode().lineCommentStart;
            var blockComment = session.getMode().blockComment;
            content = content || codiad.editor.getContent();
            
            var comments = "";
            var fn = function(startTag, endTag) {
                var start = 0, end = 0, substr = "";
                while (start != -1) {
                    start = content.indexOf(startTag, end);
                    if (start == -1) {
                        break;
                    }
                    
                    end = content.indexOf(endTag, start);
                    if (end == -1) {
                        end = content.length;
                    }
                    
                    substr = content.substring(start + startTag.length, end);
                    comments = comments.concat(" ").concat(substr);
                }
            };
            if (lineCommentStart !== "") {
                fn(lineCommentStart, "\n");
            }
            if (blockComment !== "") {
                fn(blockComment.start, blockComment.end);
            }
            
            return comments;
        },
        
        getLinesForWord: function(word, content) {
            var lines = [];
            content = content || codiad.editor.getContent();
            content = content.split("\n");
            
            for (var i = 0; i < content.length; i++) {
                var line = content[i].replace(/[^a-z0-9]/gi, " ").split(" ");
                if (line.indexOf(word) != -1) {
                    lines.push(i);
                }
            }
            
            return lines;
        },
        
        getUniqueWords: function(content) {
            var words = content.replace(/[^a-z0-9]/gi, " ").split(" ");
            var unique_words = [];
            var word = "";
            for (var i = 0; i < words.length; i++) {
                word = words[i].trim();
                if (word !== "" && unique_words.indexOf(word) == -1) {
                    unique_words.push(word);
                }
            }
            
            return unique_words;
        },
        
        getDisplayOnSave: function() {
            return false || localStorage.getItem('codiad.plugin.codespell.displayOnSave') == "true";
        },
        
        getLanguage: function() {
            return localStorage.getItem('codiad.plugin.codespell.language') || "en_US";
        },
        
        getMultiSelectionWarning: function() {
            return localStorage.getItem('codiad.plugin.codespell.multiSelect') || "true";
        },
        
        loadOwnDictionary: function() {
            var _this = this;
            if (this.typo === null) {
                return false;
            }
            
            $.getJSON(this.path + "controller.php?action=loadOwnDictionary", function(result) {
                if (result.status == "success") {
                    var parsed = _this.typo._parseDIC(result.dictionary);
                    $.each(parsed, function(i, item){
                        codiad.CodeSpell.typo.dictionaryTable[i] = item;
                    });
                }
            });
        },
        
        /**
         * @name selectLines
         * @argument (object) mistake
         */
        selectLines: function(mistake) {
            if (codiad.editor.getActive() === null) {
                return false;
            }
            var i = codiad.editor.getActive();
            
            if (mistake.lines.length > 1 && !this.getMultiSelectionWarning()) {
                //Display warning
                codiad.message.info(i18n("Warning: Multi-Selection!"));
            }
            
            i.findAll(mistake.word, {
                backwards: false,
                wrap: true,
                caseSensitive: true,
                wholeWord: true,
                regExp: false
            });
            i.scrollToRow(mistake.lines[0]);
            codiad.modal.hideOverlay();
            i.focus();
        },
        
        setDictionary: function(lang) {
            if (typeof(Typo) == 'undefined') {
                return false;
            }
            
            var settings = {
                dictionaryPath: this.path + "dictionaries"
            };
            this.typo = new Typo(lang, "", "", settings);
            return true;
        },
        
        __getSession: function() {
            return codiad.editor.getActive().getSession();
        }
    };
})(this, jQuery);
