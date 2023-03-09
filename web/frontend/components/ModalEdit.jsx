import {Modal, TextContainer} from '@shopify/polaris'
import {useAuthenticatedFetch} from "../hooks";

const ModalEdit = ({handleChange, refetchStories, clearSelection, active, type, count, selectedResources}) => {
    const fetch = useAuthenticatedFetch();

    const handler = async () => {
        if (type === 'Set active') {
            let data = new FormData();
            data.append('ids', selectedResources)
            data.append('status', 'active')

            const response = await fetch("/api/store/status", {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            handleChange()
            clearSelection()
            refetchStories()
        } else if (type === 'Set draft') {
            let data = new FormData();
            data.append('ids', selectedResources)
            data.append('status', 'draft')

            const response = await fetch("/api/store/status", {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            handleChange()
            clearSelection()
            refetchStories()
        } else {

            let data = new FormData();
            data.append('ids', selectedResources)

            const response = await fetch("/api/store/ids", {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            handleChange()
            clearSelection()
            refetchStories()
        }
    }

    const text = () => {
        if (type === 'Set active') {
            return 'Setting products as active will make them available to their selected sales channels and apps.'
        } else if (type === 'Set draft') {
            return 'Setting products as draft will hide them from all sales channels and apps.'
        } else {
            return 'This can`t be undone.'
        }
    }

    const title = () => {
        if (type === 'Set active') {
            return `Set ${count} ${
                count === 1 ? 'product' : 'products'
            } as active?`
        } else if (type === 'Set draft') {
            return `Set ${count} ${
                count === 1 ? 'product' : 'products'
            } as draft?`
        } else {
            return `Remove ${count} ${count === 1 ? 'product' : 'products'}?`
        }
    }

    return (
        <div>
            <Modal
                open={active}
                onClose={handleChange}
                title={title()}
                primaryAction={{
                    content: type,
                    onAction: handler,
                    destructive: type === 'Remove' ? true : false
                }}
                secondaryActions={[
                    {
                        content: 'Cancel',
                        onAction: handleChange
                    }
                ]}>
                <Modal.Section>
                    <TextContainer>
                        <p>{text()}</p>
                    </TextContainer>
                </Modal.Section>
            </Modal>
        </div>
    )
}

export default ModalEdit
