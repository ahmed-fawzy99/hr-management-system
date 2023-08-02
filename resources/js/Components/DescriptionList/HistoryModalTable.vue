<script setup>
import DataTable from "@/Components/DataTable.vue";
import GenericModal from "@/Components/GenericModal.vue";
import {filterKeys} from "@/Composables/filterObjectByKeys.js";


defineProps({
    undefinedPlaceholder: {
        type: String,
        default: 'N/A',
    },
    data: Object,
    exclusions: Array,
});

</script>

<template>
<!--    modalID is a random ID to prevent the modal of previewing the same data for different links     -->
  <GenericModal :modalId="Math.random().toString(36).substr(2, 5)" >
      <div v-if="data.content[0].length===0">
          No Previous Data Available
      </div>
      <div v-else>
          <DataTable
              :head="data.head"
              :data="{data: filterKeys(data.content[0], exclusions)}"
              :hasActions="false"
              :hasLink="false"
              :hasPaginator="false"
              :undefined-text="undefinedPlaceholder"
          ></DataTable>
      </div>

  </GenericModal>
</template>
