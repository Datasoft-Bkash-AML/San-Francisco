function changeStyle() {
	const targetElement = document.getElementById('cartOpenByclick');
	const targetElement2 = document.getElementById('rey-overlay');
	Object.assign(targetElement.style, {
		display: 'block',
	});
	Object.assign(targetElement2.style, {
		opacity: 1,
		'pointer-events': 'auto'
	});
}
function cartCloseByClick(){
	const targetElement = document.getElementById('cartOpenByclick');
	Object.assign(targetElement.style, {
		opacity: 0,
		transition: '.3s ease-out 0s',
		border: 0,
		padding: 0,
		display: 'none',

	});
	const targetElement2 = document.getElementById('rey-overlay');
	Object.assign(targetElement2.style, {
		opacity: 0,
		'pointer-events': 'none'
	});
}
