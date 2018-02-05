<template>
  <div class="contact:menu flex justify-end pr-4 flex-center relative">
    <!-- Trigger -->
    <div v-if="!isVisible" class="contact:actions">
      <!-- Abrir menu -->
      <div @click="open"
            :class="actionClass">
        <i class="fa fa-ellipsis-v"></i>
      </div>
    </div>

    <!-- Menu -->
    <div v-if="isVisible" class="contact:actions flex items-center justify-end absolute pin-t h-full bg-white" style="right: 1.0rem">
      
      <!-- Editar -->
      <div @click="emit('edit')"
           :class="actionClass">
        <i class="fa fa-pencil"></i>
      </div>

      <!-- Deletar -->
      <div @click="emit('destroy')"
           :class="actionClass">
        <i class="fa fa-trash"></i>
      </div>

      <!-- Fechar menu -->
      <div @click="close"
           :class="actionClass">
        <i class="fa fa-times"></i>
      </div>

    </div>
  </div>
</template>

<script>
  export default {

    props: {
      /**
       * Define a cor do texto
       * @type    {String}
       * @default 'grey-dark'
       */
      text: {
        type: String,
        default: 'grey-dark'
      },

      /**
       * Define a cor de fundo do menu
       * @type    {String}
       * @default 'grey-light'
       */
      bg: {
        type: String,
        default: 'grey-light'
      },
    },

    data() {
      return {
        isVisible: false // Detemina se o menu está visível ou não
      }
    },

    computed: {
      /**
       * actionClass
       * ---
       * Contrói o conjunto de classes do menu de ações,
       * com a cor de fundo e cor de texto
       * definida pelo componente pai.
       */
      actionClass() {
        return `contact:action flex-center h-8 w-8 text-2xl mx-2 hover:bg-${this.bg} text-${this.text} rounded-full cursor-pointer`;
      }
    },

    methods: {

      /**
       * emit
       * ---
       * Emite um evento e esconde o menu.
       * @param {String}  event Nome do evento
       */
      emit(event) {
        this.$emit(event);
        this.close();
      },

      /**
       * open
       * ---
       * Abre o menu.
       */
      open() {
        this.isVisible = true;
      },

      /**
       * close
       * ---
       * Fecha o menu.
       */
      close() {
        this.isVisible = false;
      }
      
    }
  }
</script>
