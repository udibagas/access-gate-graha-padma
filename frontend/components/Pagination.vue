<template>
	<div class="text-center py-3 bg-light">
		<el-pagination
			v-if="paginated"
			background
			layout="prev, pager, next, sizes, total"
			:current-page="pagination.current_page"
			@current-change="(p) => $emit('current-change', p)"
			@size-change="(s) => $emit('size-change', s)"
			:page-size="Number(pagination.per_page)"
			:page-sizes="pageSizes"
			:total="pagination.total"
		></el-pagination>

		<div v-else style="line-height: 32px">Total: {{ total }}</div>
	</div>
</template>

<script>
export default {
  props: ['pagination', 'paginated', 'total', 'pageSizes']
}
</script>
