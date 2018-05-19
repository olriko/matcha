<template>
	<footer>
    	ohamon & mceccato
	</footer>
</template>

<script>
	export default {
		props: {
			test: Number
		}
	}
</script>

<style type="scss" scoped>
	footer {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 0;
		border-top: 1px solid #ccc;
		padding: .5rem;
		background-color: #5a6268;
	}
</style>
