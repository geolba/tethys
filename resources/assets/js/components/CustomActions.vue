 <template>
    <div class="custom-actions">
        <button class="view" @click="itemAction('view-item', rowData, rowIndex)">
        </button>
        <a  class="edit" @click="itemAction('edit-item', rowData, rowIndex)">          
        </a>
        <a class="delete" @click="itemAction('delete-item', rowData, rowIndex)">        
        </a>
    </div>
  </template>

<script>
export default {
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  methods: {
    itemAction(action, data, index) {
      console.log("custom-actions: " + action, data.full_name, index);
    }
  }
};
</script>

<style>
.custom-actions button.ui.button {
  padding: 8px 8px;
}
.custom-actions button.ui.button > i.icon {
  margin: auto !important;
}
</style>