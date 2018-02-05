<template>
  <div class="contact flex my-2 mx-4 border shadow rounded">
    <!-- Dados do usuário -->
    <div class="contact:info flex flex-1 cursor-pointer" @click="$emit('view', contact)">

      <!-- Avatar -->
      <div class="contact:avatar flex justify-center items-start p-4">
        <o-avatar :name="contact.name"></o-avatar>
      </div>

      <!-- Dados -->
      <h3 class="flex py-4 flex-col flex-1 pl-4">
        <span>{{ contact.name }}</span>
        <small class="contact:email text-grey-dark">{{ contact.email }}</small>
      </h3>
    </div>

    <!-- Menu -->
    <o-contact-menu @edit="edit" @destroy="destroy"></o-contact-menu>

  </div>
</template>

<script>
  export default {

    props: {
      /**
       * Objeto contendo as informções do contato.
       */
      contact: { type: Object }
    },
    
    methods: {
      /**
       * edit
       * ---
       * Emite um evento para ativar o formulário
       * de edição de um contato.
       */
      edit() {
        this.$emit('edit', this.contact);
      },

      /**
       * destroy
       * ---
       * Emite um evento para deletar um contato.
       */
      destroy() {
        this.$emit('destroy', this.contact);
      }

    }
  }
</script>
