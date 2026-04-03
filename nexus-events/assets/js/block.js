/**
 * Nexus Events Gutenberg Block
 */

(function(blocks, element, editor, components) {
    'use strict';
    
    var el = element.createElement;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var RangeControl = components.RangeControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    
    blocks.registerBlockType('nexus-events/events-list', {
        title: 'Nexus Events Lijst',
        description: 'Toon een lijst van gaming events',
        icon: 'calendar-alt',
        category: 'widgets',
        keywords: ['events', 'gaming', 'nexus', 'agenda'],
        
        attributes: {
            limit: {
                type: 'number',
                default: 10
            },
            category: {
                type: 'string',
                default: ''
            },
            showPast: {
                type: 'boolean',
                default: false
            },
            order: {
                type: 'string',
                default: 'ASC'
            }
        },
        
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            
            function onChangeLimit(newLimit) {
                setAttributes({ limit: newLimit });
            }
            
            function onChangeCategory(newCategory) {
                setAttributes({ category: newCategory });
            }
            
            function onChangeShowPast(newShowPast) {
                setAttributes({ showPast: newShowPast });
            }
            
            function onChangeOrder(newOrder) {
                setAttributes({ order: newOrder });
            }
            
            // Fetch games for category selection
            var gameOptions = [
                { label: 'Alle Games', value: '' }
            ];
            
            // In a real implementation, you would fetch this from the API
            // For now, we'll add some common gaming categories
            var commonGames = [
                'Valorant',
                'League of Legends',
                'CS:GO',
                'Fortnite',
                'Minecraft',
                'Among Us',
                'Apex Legends',
                'Overwatch 2'
            ];
            
            commonGames.forEach(function(game) {
                gameOptions.push({
                    label: game,
                    value: game.toLowerCase().replace(/\s+/g, '-')
                });
            });
            
            return el('div', { className: props.className }, [
                el('div', { 
                    className: 'nexus-events-block-preview',
                    style: {
                        padding: '20px',
                        border: '2px dashed #ddd',
                        borderRadius: '8px',
                        textAlign: 'center',
                        backgroundColor: '#f9f9f9'
                    }
                }, [
                    el('div', { 
                        style: { 
                            fontSize: '24px', 
                            marginBottom: '10px',
                            color: '#333'
                        } 
                    }, '🎮'),
                    el('h3', { 
                        style: { 
                            margin: '0 0 10px 0',
                            color: '#333'
                        } 
                    }, 'Nexus Events Lijst'),
                    el('p', { 
                        style: { 
                            margin: '0',
                            color: '#666',
                            fontSize: '14px'
                        } 
                    }, 'Toont ' + attributes.limit + ' events' + 
                        (attributes.category ? ' in ' + attributes.category : '') +
                        (attributes.showPast ? ' inclusief verleden' : ' alleen toekomstig')),
                ]),
                
                el(InspectorControls, {},
                    el(PanelBody, {
                        title: 'Events Instellingen',
                        initialOpen: true
                    }, [
                        el(RangeControl, {
                            label: 'Aantal Events',
                            value: attributes.limit,
                            onChange: onChangeLimit,
                            min: 1,
                            max: 50,
                            help: 'Maximaal aantal events om te tonen'
                        }),
                        
                        el(SelectControl, {
                            label: 'Game Categorie',
                            value: attributes.category,
                            onChange: onChangeCategory,
                            options: gameOptions,
                            help: 'Filter events op specifieke game'
                        }),
                        
                        el(ToggleControl, {
                            label: 'Toon Verleden Events',
                            checked: attributes.showPast,
                            onChange: onChangeShowPast,
                            help: 'Toon ook events die al voorbij zijn'
                        }),
                        
                        el(SelectControl, {
                            label: 'Sorteer Volgorde',
                            value: attributes.order,
                            onChange: onChangeOrder,
                            options: [
                                { label: 'Oplopend (eerste eerst)', value: 'ASC' },
                                { label: 'Aflopend (laatste eerst)', value: 'DESC' }
                            ],
                            help: 'Bepaal de sorteervolgorde van events'
                        })
                    ])
                )
            ]);
        },
        
        save: function(props) {
            // This block is dynamic, so we return null
            return null;
        }
    });
    
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.editor,
    window.wp.components
);
