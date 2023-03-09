import React, {useCallback} from 'react'
import {
    Modal,
    Thumbnail,
    DropZone,
    Stack,
    ButtonGroup,
    Button
} from '@shopify/polaris'
import {useAuthenticatedFetch} from "../hooks";
import {useNavigate} from "react-router-dom";

const ModalDropZone = ({
                           files,
                           refetchStories,
                           setFiles,
                           activeModal,
                           handleChangeActiveModal
                       }) => {
    const navigate = useNavigate()
    const fetch = useAuthenticatedFetch();

    const handleDropZoneDrop = useCallback(
        (_dropFiles, acceptedFiles, _rejectedFiles) =>
            setFiles([...acceptedFiles]),
        []
    )

    const fileUpload = !files.length && (
        <DropZone.FileUpload actionHint='Accepts .csv'/>
    )

    const handleClearDropZone = useCallback(() => {
        setFiles([])
    }, [setFiles])

    const handleSaveFile = async () => {
        let data = new FormData()
        data.append('file', files[0])

        const response = await fetch("/api/export-file", {
            method: 'POST',
            body: data
        });

        const result = await response.json();

        handleChangeActiveModal();
        refetchStories();
    }

    const uploadedFiles = files.length > 0 && (
        <Stack vertical>
            {files.map((file, index) => (
                <Stack alignment='center' key={index}>
                    <div>
                        {file.name}{' '}
                        <p variant='bodySm' as='p'>
                            {file.size} bytes
                        </p>
                    </div>
                </Stack>
            ))}
        </Stack>
    )
    return (
        <Modal
            open={activeModal}
            onClose={handleChangeActiveModal}
            title='Export files'>
            <Modal.Section>
                <DropZone
                    onDrop={handleDropZoneDrop}
                    type='file'
                    accept='text/csv'
                    variableHeight>
                    {uploadedFiles}
                    {fileUpload}
                </DropZone>
            </Modal.Section>
            <Modal.Section>
                <div
                    style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'flex-end'
                    }}>
                    <ButtonGroup>
                        <Button onClick={handleClearDropZone}>Clear</Button>
                        <Button primary onClick={handleSaveFile}>
                            Save
                        </Button>
                    </ButtonGroup>
                </div>
            </Modal.Section>
        </Modal>
    )
}

export default ModalDropZone
