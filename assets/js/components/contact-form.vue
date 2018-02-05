<template>
  <div class="contact:form p-4">
    <h3 v-if="form.action === 'edit'">Editar contato</h3>
    <h3 v-if="form.action === 'create'">Criar contato</h3>

    <!-- Nome -->
    <div class="form-component my-4">
      <label for="name" class="font-bold text-sm">Nome<span class="text-red text-lg">*</span></label>
      <input type="text" id="name" class="input border-b w-full p-4 transition-all" v-model="form.name" required />
      <div v-if="errors['name']" class="flex"><span class="text-red font-bold text-xs mt-2" v-text="errors['name']"></span></div>
    </div>

    <!-- Email -->
    <div class="form-component my-4">
      <label for="email" class="font-bold text-sm">E-mail<span class="text-red text-lg">*</span></label>
      <input type="email" id="email" class="input border-b w-full p-4 transition-all" v-model="form.email" required />
      <div v-if="errors['email']" class="flex"><span class="text-red font-bold text-xs mt-2" v-text="errors['email']"></span></div>
    </div>

    <!-- Endereço -->
    <div class="form-component my-4">
      <label for="address" class="font-bold text-sm">Endereço<span class="text-red text-lg">*</span></label>
      <input type="text" id="address" class="input border-b w-full p-4 transition-all" v-model="form.address" required />
      <div v-if="errors['address']" class="flex"><span class="text-red font-bold text-xs mt-2" v-text="errors['address']"></span></div>
    </div>

    <!-- Telefones -->
    <label for="phone" class="font-bold text-sm my-4">Telefones<span class="text-red text-lg">*</span></label>
    <div v-if="errors['phone']" class="flex"><span class="text-red font-bold text-xs mt-2" v-text="errors['phone']"></span></div>
    <div class="form-component my-4">
      <div class="flex-center my-4" v-for="(phone, key) in form.phone" :key="`phone-${key}-${phone}`">
        <input
          type="text"
          :ref="`phone-${key}`"
          placeholder="Telefone"
          class="input border-b w-full p-4 transition-all"
          v-model="phone.number" />
        <span class="text-grey-dark hover:text-red cursor-pointer" @click="removePhone(key)" v-if="key > 0"><i class="fa fa-times"></i></span>
      </div>
      <div @click="addPhone" class="text-grey-dark border-b w-full p-4">Adicionar número</div>
    </div>

    <!-- Botão de submeter -->
    <div class="flex justify-end w-full">
      <button @click="submit" class="my-8 py-4 px-8 w-full sm:w-auto text-white bg-primary hover:bg-primary-light rounded shadow hover:shadow-md transition-all">salvar</button>
    </div>
  </div>
</template>

<script>
  export default {
    
    props: {
      /**
       * Objeto contendo dados do contato a ser editado
       * ou propriedades em branco para a criação
       * de um novo contato.
       */
      form: { type: Object }
    },

    data() {
      return {
        /**
         * Objeto contendo erros de preenchimento
         * do formulário.
         */
        errors: {}
      }
    },
    
    methods: {
      /**
       * addPhone
       * ---
       * Adiciona um novo campo de número de telefone.
       */
      addPhone() {
        const id = this.form.phone.length;
        this.form.phone.push({number:''});
        this.$nextTick(() => {
          this.$refs[`phone-${id}`][0].focus();
        });
      },

      /**
       * removePhone
       * ---
       * Remove um campo de número de telefone.
       */
      removePhone(key) {
        this.form.phone = this.form.phone.filter((phone, id) => id !== key);
      },

      /**
       * submit
       * ---
       * Aciona a validação dos dados do formulário e emite
       * um evento para a submissão dos dados
       * para o back-end.
       */
      submit() {
        this.validateForm();
        if(Object.keys(this.errors).length === 0) this.$emit(this.form.action);
      },

      /**
       * validateForm
       * ---
       * Realiza a validação dos dados do formulário.
       */
      validateForm() {
        this.errors = {};
        for(let field in this.form) {
          let value = this.form[field];
          if(value.length === 0) this.$set(
              this.errors,
              field,
              `O campo ${field} é obrigatório`
            );
        }
      }
      
    }
  }
</script>
