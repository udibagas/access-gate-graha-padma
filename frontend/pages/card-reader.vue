<template>
  <el-card :body-style="{ padding: '0px' }">
    <div slot="header" class="d-flex justify-content-between">
      <h3 class="text-muted mt-2">CARD READER</h3>
      <el-form inline @submit.native.prevent>
        <el-form-item class="mb-0">
          <el-button
            @click="openForm({})"
            type="primary"
            icon="el-icon-plus"
            size="small"
            title="Tambah Gate"
            >TAMBAH READER</el-button
          >
        </el-form-item>
        <el-form-item class="mb-0">
          <el-input
            v-model="keyword"
            placeholder="Cari"
            prefix-icon="el-icon-search"
            clearable
            size="small"
            @change="
              () => {
                this.pagination.current_page = 1
                fetchData()
              }
            "
          ></el-input>
        </el-form-item>
      </el-form>
    </div>

    <el-table
      height="calc(100vh - 171px)"
      stripe
      v-loading="loading"
      :data="tableData"
      @sort-change="sortChange"
      @filter-change="filterChange"
      :default-sort="{ prop: 'name', order: 'ascending' }"
    >
      <el-table-column
        type="index"
        label="#"
        :index="paginated ? pagination.from : 1"
      ></el-table-column>
      <el-table-column prop="access_gate.name" label="Gate"></el-table-column>
      <el-table-column prop="name" label="Name"></el-table-column>
      <el-table-column prop="prefix" label="Prefix"></el-table-column>
      <el-table-column prop="type" label="Jenis"></el-table-column>
      <el-table-column label="Kamera">
        <template slot-scope="{ row }">
          {{ row.camera_names?.join(', ') }}
        </template>
      </el-table-column>

      <ActionColumn
        @refreshData="refreshData"
        @openForm="openForm"
        @deleteData="deleteData"
      />
    </el-table>

    <Pagination
      @current-change="currentChange"
      @size-change="sizeChange"
      :page-sizes="pageSizes"
      :pagination="pagination"
      :paginated="paginated"
      :total="tableData.length"
    />

    <el-dialog
      title="CARD READER"
      width="450px"
      :visible.sync="showForm"
      :before-close="closeForm"
      :close-on-click-modal="false"
    >
      <el-form label-width="120px" label-position="left">
        <el-form-item label="Name" :error="errors.name?.join(',')">
          <el-input v-model="form.name" placeholder="Name"></el-input>
        </el-form-item>

        <el-form-item label="Gate" :error="errors.access_gate_id?.join(',')">
          <el-select
            style="width: 100%"
            v-model="form.access_gate_id"
            placeholder="Gate"
          >
            <el-option
              v-for="g in accessGateList"
              :key="g.id"
              :value="g.id"
              :label="g.name"
            ></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Prefix" :error="errors.prefix?.join(',')">
          <el-input v-model="form.prefix" placeholder="Prefix"></el-input>
        </el-form-item>

        <el-form-item label="Jenis" :error="errors.type?.join(',')">
          <el-select
            v-model="form.type"
            placeholder="Jenis"
            style="width: 100%"
          >
            <el-option label="IN" value="IN"></el-option>
            <el-option label="OUT" value="OUT"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Kamera" :error="errors.camera_ids?.join(',')">
          <el-select
            style="width: 100%"
            v-model="form.camera_ids"
            placeholder="Kamera"
            filterable
            default-first-option
            remote
            clearable
            multiple
            :remote-method="(q) => getList('/api/camera', 'cameraList', q)"
          >
            <el-option
              v-for="camera in cameraList"
              :key="camera.id"
              :value="camera.id"
              :label="camera.name"
            ></el-option>
          </el-select>
        </el-form-item>
      </el-form>

      <div slot="footer">
        <el-button icon="el-icon-circle-close" @click="closeForm">
          BATAL
        </el-button>

        <el-button
          type="primary"
          icon="el-icon-success"
          @click="save"
          :loading="loading"
        >
          SIMPAN
        </el-button>
      </div>
    </el-dialog>
  </el-card>
</template>

<script>
import crud from '../mixins/crud'
import dropdown from '../mixins/dropdown'

export default {
  middleware: 'admin',
  mixins: [crud, dropdown],

  created() {
    this.getList('/api/camera', 'cameraList')
    this.getList('/api/accessGate', 'accessGateList')
  },

  data() {
    return {
      url: '/api/cardReader',
    }
  },
}
</script>
