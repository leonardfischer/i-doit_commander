window.Commander = Class.create({
    initialize: function () {
        this.active = false;
        this.query = '';
        this.executing = false;
        
        this.$overlay = $('body').down('.lfischer-overlay');
        this.$commander = $('body').down('.lfischer-commander');
        this.$input = $('body').down('.lfischer-commander-input');
        this.$indicator = $('body').down('.lfischer-commander-indicator');
        this.$choices = $('body').down('.lfischer-commander-choices');
        this.$subtext = $('body').down('.lfischer-commander-subtext');
        
        if (!this.$overlay) {
            this.$overlay = new Element('div', {className: 'lfischer-overlay hide'});
            
            $('body').insert(this.$overlay);
        }
        
        if (!this.$commander) {
            this.$input = new Element('input', {className: 'lfischer-commander-input input'});
            this.$input.on('keydown', this.keyDown.bind(this));
            this.$indicator = new Element('img', {className: 'lfischer-commander-indicator', src: window.dir_images + 'ajax-loading.gif'});
            this.$choices = new Element('div', {className: 'lfischer-commander-choices autocomplete'});
    
            new Ajax.Autocompleter(this.$input, this.$choices, window.www_dir + 'lfischer_commander/ajax/suggest', {
                paramName: 'query',
                minChars: 3,
                indicator: this.$indicator,
                afterUpdateElement: function(input, li) {
                    // Re-set the current query, because it will have been replaced by the autocompleter.
                    this.$input.setValue(this.query);
                    this.executing = true;
    
                    this.force(li.readAttribute('data-class'));
                }.bind(this)
            });
            
            this.$subtext = new Element('div', {className: 'lfischer-commander-subtext'});
            
            this.$commander = new Element('div', {className: 'lfischer-commander hide'})
                .update(new Element('img', {src: window.dir_images + 'icons/close-circle.png', className: 'close'}))
                .insert(new Element('h2').update(idoit.Translate.get('lfischer_commander.commander')))
                .insert(this.$input)
                .insert(this.$indicator.hide())
                .insert(this.$choices)
                .insert(this.$subtext);
    
            this.$subtext.on('click', 'li', function (ev) {
                this.force(ev.findElement('li').readAttribute('data-task'));
            }.bind(this));
            
            this.$commander.down('img').on('click', this.hide.bind(this));
            
            $('body').insert(this.$commander);
        }
    },
    
    show: function () {
        this.active = true;
        
        this.$subtext
            .update(new Element('p', {className: 'text-grey'}).update(idoit.Translate.get('lfischer_commander.commander-for-example')))
            .insert(new Element('p', {className: 'text-grey pt5 pl10'}).update(idoit.Translate.get('lfischer_commander.commander-examples')));
        
        this.$overlay.removeClassName('hide');
        this.$commander.removeClassName('hide');
        
        this.$input.focus();
        this.$input.select();
    },
    
    hide: function () {
        this.active = false;
        
        this.$overlay.addClassName('hide');
        this.$commander.addClassName('hide');
    },
    
    keyDown: function (ev) {
        if (ev.key === 'Escape') {
            this.hide();
        }
        
        this.query = this.$input.getValue();
        
        if (ev.key === 'Enter') {
            this.sendQuery();
        }
    },
    
    force: function(task) {
        this.$indicator.show();
        
        new Ajax.Request(window.www_dir + 'lfischer_commander/ajax/force', {
            parameters: {
                class: task,
                query: this.query
            },
            onComplete: function (xhr) {
                if (!is_json_response(xhr, true)) {
                    return
                }
                
                const json = xhr.responseJSON;
                
                if (!json.success) {
                    idoit.Notify.error(json.message, {life: 10});
                    return;
                }
                
                const data = json.data;
                
                if (data.executed) {
                    this.executing = true;
                    
                    eval(data.code);
                } else {
                    idoit.Notify.warning(data.message, {life: 10});
                }
            }.bind(this)
        });
    },
    
    sendQuery: function () {
        delay(function () {
            if (this.executing) {
                return;
            }
            
            new Ajax.Request(window.www_dir + 'lfischer_commander/ajax/query', {
                parameters: {
                    query: this.query
                },
                onComplete: function (xhr) {
                    if (!is_json_response(xhr, true)) {
                        return
                    }
                    
                    const json = xhr.responseJSON;
                    
                    if (!json.success) {
                        idoit.Notify.error(json.message, {life: 10});
                        return;
                    }
                    
                    const data = json.data;
                    
                    if (data.executed) {
                        this.executing = true;
                        
                        eval(data.code);
                    } else {
                        this.$subtext
                            .update(new Element('p').update(data.message))
                            .insert(new Element('ul'));
                        
                        if (data.hasOwnProperty('tasks')) {
                            let i;
                            
                            for (i in data.tasks) {
                                if (!data.tasks.hasOwnProperty(i)) {
                                    continue;
                                }
                                
                                this.$subtext
                                    .down('ul')
                                    .insert(new Element('li', {'data-task': data.tasks[i].class})
                                        .update(data.tasks[i].name));
                            }
                        }
                    }
                }.bind(this)
            });
        }.bind(this), 100)
    }
});
