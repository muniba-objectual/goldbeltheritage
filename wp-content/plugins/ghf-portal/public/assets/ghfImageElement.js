jQuery(document).ready(function($){
    if(!window.fbControls) window.fbControls = new Array();
    window.fbControls.push(function (controlClass){

        /** 
         * Image Element 
         * @since 2.0 
         * 
         */
        class controlImageElement extends controlClass{
            static get definition() {
                return {
                  icon: '<img src="https://mygoldbeltheritage.org/wp-content/uploads/2022/02/picture-e1644332992612.png"/>',
                  i18n: {
                    default: 'Image',
                  },
                }
            }
            build(){
                return this.markup('img',null,{id:this.config.name});
            }

            onRender(){
                let value = this.config.src || 'https://via.placeholder.com/350x150';
                $("#" + this.config.name).attr('src',this.config.value);
                console.log(this);
            }
        }

        controlClass.register('imageElement',controlImageElement);
        return controlImageElement;
    })
})