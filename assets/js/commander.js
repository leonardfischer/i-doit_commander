window.Commander = Class.create({
    initialize: function () {
        this.active = false;
        
        this.$overlay = $('body').down('.lfischer-overlay');
        this.$commander = $('body').down('.lfischer-commander');
        this.$input = $('body').down('.lfischer-commander-input');
        this.$subtext = $('body').down('.lfischer-commander-subtext');
        
        if (!this.$overlay) {
            this.$overlay = new Element('div', {className: 'lfischer-overlay hide'});
            
            $('body').insert(this.$overlay);
        }
        
        if (!this.$commander) {
            this.$input = new Element('input', {className: 'lfischer-commander-input input'});
            this.$input.on('keydown', this.keyDown.bind(this));
            
            this.$subtext = new Element('div', {className: 'lfischer-commander-subtext'});
            
            this.$commander = new Element('div', {className: 'lfischer-commander hide'})
                .update(new Element('img', {src: window.dir_images + 'icons/close-circle.png', className: 'close'}))
                .insert(new Element('h2').update(idoit.Translate.get('lfischer_commander.commander')))
                .insert(this.$input)
                .insert(this.$subtext);
    
            this.$subtext.on('click', 'li', function (ev) {
                const $li = ev.findElement('li');
                
                new Ajax.Request(window.www_dir + 'lfischer_commander/ajax/force', {
                    parameters: {
                        class: $li.readAttribute('data-classname'),
                        query: $li.readAttribute('data-query')
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
                            eval(data.code);
                        } else {
                            idoit.Notify.warning(data.message, {life: 10});
                        }
                    }.bind(this)
                })
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
        
        if (ev.key === 'Enter') {
            new Ajax.Request(window.www_dir + 'lfischer_commander/ajax/query', {
                parameters: {
                    query: this.$input.getValue()
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
                                    .insert(new Element('li', {'data-classname': data.tasks[i].class, 'data-query': data.query})
                                        .update(data.tasks[i].name));
                            }
                        }
                    }
                }.bind(this)
            });
        }
    }
});
