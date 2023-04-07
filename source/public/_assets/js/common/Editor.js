var Editor = {
    

    set : function( target ){
        var reteditor;
        var Mentionslists ={};

        ClassicEditor
        .create( document.querySelector( target ), {
            removePlugins: ['Title'],
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    '|',
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'fontBackgroundColor',
                    'fontColor',
                    'fontSize',
                    'fontFamily',
                    'highlight',
                    'underline',
                    '|',
                    'horizontalLine',
                    'bulletedList',
                    'numberedList',
                    'alignment',
                    '|',
                    'pageBreak',
                    'indent',
                    'outdent',
                    'restrictedEditingException',
                    '|',
                    'link',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'MathType',
                    'ChemType',
                    'specialCharacters',
                    'strikethrough',
                    'subscript',
                    'superscript',
                    '|',
                    'exportPdf',
                    '|',
                    'code',
                    'codeBlock'
                ]
            },
            language: 'ko',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:full',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                ]
            },
            licenseKey: '',
            mention: {
                feeds:[
                    {
                        marker:'@',
                        feed: Editor.getMention_lists,
                        itemRenderer: Editor.setMention_render
                    }
                ]
            }
            
        } )
        .then( editor => {
            window.editor = editor;
            editor.plugins.get("FileRepository").createUploadAdapter = function (loader) {
                return new MyUploadAdapter( loader );
            };
            MentionCustomization(editor);
        
        } )
        .catch( error => {
            console.error( 'Oops, something gone wrong!' );
            /*console.error( 'Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:' );*/
            console.warn( 'Build id: ouxr1d4gi1cg-aij3pyu3erak' );
            console.error( error );
        } );

    },

    getMention_lists : function( String ){
        if( !Mentionsitems ){
            requestAjax.loading_flag = false;
            var datas = {};
            var res = requestAjax.request('/info/user/listmention',datas,'POST','JSON',false);
            Mentionsitems = res.data.items;
            requestAjax.loading_flag = true;
        }


        return new Promise( resolve => {
            setTimeout( () => {
                const itemsToDisplay = Mentionsitems
                    .filter( isItemMatching )
                    .slice( 0, 10 );

                resolve( itemsToDisplay );
            }, 100 );
        } );

        function isItemMatching( item ) {
            const searchString = String.toLowerCase();

            return (
                item.name.toLowerCase().includes( searchString ) ||
                item.id.toLowerCase().includes( searchString )
            );
        }
    },

    


    setMention_render : function( item ){
        const itemElement = document.createElement( 'span' );
    
        itemElement.classList.add( 'custom-item' );
        itemElement.id = `mention-list-item-id-${ item.userId }`;
        itemElement.textContent = `${ item.id } `;
    
        const usernameElement = document.createElement( 'span' );
    
        usernameElement.classList.add( 'custom-item-username' );
        usernameElement.textContent = item.sosok_name;
    
        itemElement.appendChild( usernameElement );
    
        return itemElement;
    }
}
var Mentionsitems;

class MyUploadAdapter {
    constructor( loader ) {
        // The file loader instance to use during the upload.
        this.loader = loader;
    }

    // Starts the upload process.
    upload() {
        return this.loader.file
            .then( file => new Promise( ( resolve, reject ) => {
                this._initRequest();
                this._initListeners( resolve, reject, file );
                this._sendRequest( file );
            } ) );
    }

    // Aborts the upload process.
    abort() {
        if ( this.xhr ) {
            this.xhr.abort();
        }
    }

    // Initializes the XMLHttpRequest object using the URL passed to the constructor.
    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();

        // Note that your request may look different. It is up to you and your editor
        // integration to choose the right communication channel. This example uses
        // a POST request with JSON as a data structure but your configuration
        // could be different.
        xhr.open( 'POST', '/common/attach/uploadeditor', true );
        xhr.responseType = 'json';
    }

    // Initializes XMLHttpRequest listeners.
    _initListeners( resolve, reject, file ) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${ file.name }.`;

        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
        xhr.addEventListener( 'abort', () => reject() );
        xhr.addEventListener( 'load', () => {
            const response = xhr.response;

            // This example assumes the XHR server's "response" object will come with
            // an "error" which has its own "message" that can be passed to reject()
            // in the upload promise.
            //
            // Your integration may handle upload errors in a different way so make sure
            // it is done properly. The reject() function must be called when the upload fails.
            if ( !response || response.error ) {
                return reject( response && response.error ? response.error.message : genericErrorText );
            }

            // If the upload is successful, resolve the upload promise with an object containing
            // at least the "default" URL, pointing to the image on the server.
            // This URL will be used to display the image in the content. Learn more in the
            // UploadAdapter#upload documentation.
            resolve( {
                default: response.url
            } );
        } );

        // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
        // properties which are used e.g. to display the upload progress bar in the editor
        // user interface.
        if ( xhr.upload ) {
            xhr.upload.addEventListener( 'progress', evt => {
                if ( evt.lengthComputable ) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            } );
        }
    }

    // Prepares the data and sends the request.
    _sendRequest( file ) {
        // Prepare the form data.
        const data = new FormData();

        data.append( 'upload', file );

        // Important note: This is the right place to implement security mechanisms
        // like authentication and CSRF protection. For instance, you can use
        // XMLHttpRequest.setRequestHeader() to set the request headers containing
        // the CSRF token generated earlier by your application.

        // Send the request.
        this.xhr.send( data );
    }
}


function setMentionItemRender(){    
    requestAjax.loading_flag = false;
    var datas = {};
    //var res = requestAjax.request('/info/user/listmention',datas,'POST','JSON',false);
    //mentions_items = res.data.items;
    $.getJSON("/info/user/listmention", function(json) {
        mentions_items = json.data.items;
    });
    requestAjax.loading_flag = true;
}


function MentionCustomization( editor ) {
    // The upcast converter will convert view <a class="mention" href="" data-user-id="">
    // elements to the model 'mention' text attribute.
    editor.conversion.for( 'upcast' ).elementToAttribute( {
        view: {
            name: 'span',
            key: 'data-mention',
            classes: 'mention',
            attributes: {
                'data-user-id': true
            }
        },
        model: {
            key: 'mention',
            value: viewItem => {
                // The mention feature expects that the mention attribute value
                // in the model is a plain object with a set of additional attributes.
                // In order to create a proper object use the toMentionAttribute() helper method:
                const mentionAttribute = editor.plugins.get( 'Mention' ).toMentionAttribute( viewItem, {
                    // Add any other properties that you need.
                    uno: viewItem.getAttribute( 'data-user-id' )
                } );

                return mentionAttribute;
            }
        },
        converterPriority: 'high'
    } );

    // Downcast the model 'mention' text attribute to a view <a> element.
    editor.conversion.for( 'downcast' ).attributeToElement( {
        model: 'mention',
        view: ( modelAttributeValue, viewWriter ) => {
            // Do not convert empty attributes (lack of value means no mention).
            if ( !modelAttributeValue ) {
                return;
            }

            return viewWriter.createAttributeElement( 'span', {
                class: 'mention',
                'data-mention': modelAttributeValue.id,
                'data-user-id': modelAttributeValue.uno,
            }, {
                // Make mention attribute to be wrapped by other attribute elements.
                priority: 20,
                // Prevent merging mentions together.
                id: modelAttributeValue.uid
            } );
        },
        converterPriority: 'high'
    } );
}